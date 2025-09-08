<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Simulado; // model para tentativas
use App\Models\SimuladoAnswer;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuizController extends Controller
{
    public function simulado(Request $request)
    {
        $userId = Auth::id();

        $allowed = [5, 10, 15, 20, 40, 60];
        $qtd = intval($request->input('qtd', 10));
        if (!in_array($qtd, $allowed)) $qtd = 10;

        // timer: em minutos (10,20,30,60 etc)
        $timerMin = intval($request->input('timer', 20));
        $timerSec = $timerMin > 0 ? $timerMin * 60 : null;

        $selectedCategories = $request->input('categories', []);

        $query = Question::query();
        if (!empty($selectedCategories)) {
            $query->whereIn('category_id', $selectedCategories);
        }
        $query->where('correct_count', '<=', 4); // filtrar questões com até 4 acertos

        if ($userId) {
            $query->where('user_id', $userId);
        }

        // pegar questões e embaralhar opções no servidor, gerando também o mapa shuffled => original
        $questions = $query->inRandomOrder()->take($qtd)->get()->map(function ($q) {
            $options = $q->options ?? [];
            $origCorrectKey = $q->correct_answer;

            // criar pares [origKey, value] e embaralhar os pares — evita problemas com values duplicados
            $pairs = collect($options)->map(function ($value, $key) {
                return ['orig_key' => $key, 'value' => $value];
            })->shuffle()->values();

            $letters = range('a', 'z');
            $mapped = [];
            $shuffledMap = []; // shuffled_key => original_key
            $newCorrect = null;

            foreach ($pairs as $i => $pair) {
                $newKey = $letters[$i];
                $mapped[$newKey] = $pair['value'];
                $shuffledMap[$newKey] = $pair['orig_key'];

                if ($pair['orig_key'] === $origCorrectKey) {
                    $newCorrect = $newKey;
                }
            }

            // clonar para não alterar o model original
            $clone = clone $q;
            $clone->options = $mapped;
            $clone->correct_answer = $newCorrect;
            // mapa que será enviado ao front para permitir a correção no servidor
            $clone->shuffled_map = $shuffledMap;

            return $clone;
        });

        if ($questions->isEmpty()) {
            return redirect()->route('quiz')->with('success', 'Nenhuma questão disponível para o simulado com os filtros selecionados.');
        }

        // cria tentativa (status inicial) para referenciar
        $simulado = Simulado::create([
            'user_id' => $userId,
            'questions_count' => $questions->count(),
            'time_seconds' => $timerSec,
            'categories' => !empty($selectedCategories) ? $selectedCategories : null,
        ]);

        // mantém exatamente as variáveis que você já usava
        return view('questions.simulado', compact('questions', 'qtd', 'selectedCategories', 'timerMin', 'simulado'));
    }

    public function corrigirSimulado(Request $request)
    {
        $userId = Auth::id();

        $questionIds = $request->input('question_ids', []);
        $answers = $request->input('answers', []); // enviado com keys = question_id, value = shuffled_key escolhida
        $shuffledMaps = $request->input('shuffled_maps', []); // shuffled_maps[question_id] = { shuffledKey: originalKey, ... }
        $simuladoId = $request->input('simulado_id');

        $simulado = $simuladoId ? Simulado::find($simuladoId) : null;

        $erradas = [];
        $acertos = 0;
        $total = count($questionIds);

        foreach ($questionIds as $qid) {
            $q = Question::find($qid); // objeto original do DB (tem correct_answer original)
            if (!$q) continue;

            $givenShuffled = isset($answers[$qid]) ? $answers[$qid] : null;

            // traduzir a alternativa enviada (chave embaralhada) para a chave original usando o shuffled_map enviado pelo front
            $origGiven = null;
            if ($givenShuffled !== null && isset($shuffledMaps[$qid]) && is_array($shuffledMaps[$qid])) {
                $map = $shuffledMaps[$qid]; // ex: ['a' => 'c', 'b' => 'a', ...]
                if (array_key_exists($givenShuffled, $map)) {
                    $origGiven = $map[$givenShuffled]; // chave original (ex: 'c')
                }
            }

            // fallback: se não existiu mapa, tentamos usar o valor direto (pior caso)
            if ($origGiven === null && $givenShuffled !== null) {
                if (isset($q->options[$givenShuffled])) {
                    $origGiven = $givenShuffled;
                } else {
                    $origGiven = $givenShuffled; // mantém o que veio (não ideal)
                }
            }

            // verificar com a chave original do banco
            $isCorrect = $q->isCorrectAnswer($origGiven);

            if ($isCorrect) {
                $q->increment('correct_count');
                $acertos++;
            } else {
                $q->increment('wrong_count');
            }

            // salva resposta da tentativa se houver simulado
            if ($simulado) {
                $storedGiven = $origGiven ?? $givenShuffled;
                SimuladoAnswer::create([
                    'simulado_id' => $simulado->id,
                    'question_id' => $q->id,
                    'given_answer' => $storedGiven,
                    'is_correct' => $isCorrect,
                ]);
            }

            if (!$isCorrect) {
                $userText = $origGiven ? (strtoupper($origGiven) . ' - ' . ($q->options[$origGiven] ?? '')) : ($givenShuffled ? (strtoupper($givenShuffled) . ' - ' . ($q->options[$givenShuffled] ?? '')) : 'Sem resposta');
                $erradas[] = [
                    'id' => $q->id,
                    'question' => $q->question_text,
                    'resposta' => $userText,
                    'correta' => strtoupper($q->correct_answer) . ' - ' . ($q->options[$q->correct_answer] ?? ''),
                    'reason'   => $q->reason
                ];
            }
        }

        // atualiza estatísticas do simulado
        if ($simulado) {
            $simulado->update([
                'correct_count' => $acertos,
                'wrong_count' => ($total - $acertos),
                'duration_seconds' => $request->input('duration_seconds') ?? null,
            ]);
        }

        return response()->json([
            'total' => $total,
            'acertos' => $acertos,
            'erradas_count' => count($erradas),
            'erradas' => $erradas,
            'simulado_id' => $simulado ? $simulado->id : null,
        ]);
    }

    // refazer um simulado existente: recria um simulado com mesmas categorias e qtd
    public function refazer(Simulado $simulado)
    {
        $qtd = $simulado->questions_count ?? 10;
        $selectedCategories = $simulado->categories ?? [];
        $timerMin = $simulado->time_seconds ? intval($simulado->time_seconds / 60) : 20;

        $query = Question::query();
        if (!empty($selectedCategories)) $query->whereIn('category_id', $selectedCategories);
        if ($simulado->user_id) $query->where('user_id', $simulado->user_id);

        $questions = $query->inRandomOrder()->take($qtd)->get()->map(function ($q) {
            $options = $q->options ?? [];
            $origCorrectKey = $q->correct_answer;

            $pairs = collect($options)->map(function ($value, $key) {
                return ['orig_key' => $key, 'value' => $value];
            })->shuffle()->values();

            $letters = range('a', 'z');
            $mapped = [];
            $shuffledMap = [];
            $newCorrect = null;

            foreach ($pairs as $i => $pair) {
                $newKey = $letters[$i];
                $mapped[$newKey] = $pair['value'];
                $shuffledMap[$newKey] = $pair['orig_key'];

                if ($pair['orig_key'] === $origCorrectKey) {
                    $newCorrect = $newKey;
                }
            }

            $clone = clone $q;
            $clone->options = $mapped;
            $clone->correct_answer = $newCorrect;
            $clone->shuffled_map = $shuffledMap;

            return $clone;
        });

        $newSimulado = Simulado::create([
            'user_id' => $simulado->user_id,
            'questions_count' => $qtd,
            'time_seconds' => $simulado->time_seconds,
            'categories' => $selectedCategories,
        ]);

        return view('questions.simulado', compact('questions', 'qtd', 'selectedCategories', 'timerMin', 'newSimulado'));
    }

    // exporta as erradas de um simulado em CSV
    public function exportErradas(Simulado $simulado)
    {
        $answers = $simulado->answers()->where('is_correct', false)->with('question')->get();

        $response = new StreamedResponse(function () use ($answers) {
            $handle = fopen('php://output', 'w');
            // cabeçalho
            fputcsv($handle, ['Question ID', 'Question Text', 'Given Answer', 'Correct Answer', 'Reason']);
            foreach ($answers as $a) {
                $q = $a->question;
                fputcsv($handle, [
                    $q->id,
                    strip_tags($q->question_text),
                    $a->given_answer,
                    strtoupper($q->correct_answer) . ' - ' . ($q->options[$q->correct_answer] ?? ''),
                    str_replace(["\r", "\n"], ' ', strip_tags($q->reason))
                ]);
            }
            fclose($handle);
        });

        $filename = 'simulado_' . $simulado->id . '_erros.csv';
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        return $response;
    }
}
