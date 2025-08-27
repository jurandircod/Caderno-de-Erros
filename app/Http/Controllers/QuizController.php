<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Simulado; // criará o model abaixo
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
        if ($userId) {
            $query->where('user_id', $userId);
        }

        $questions = $query->inRandomOrder()->take($qtd)->get();

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

        return view('questions.simulado', compact('questions', 'qtd', 'selectedCategories', 'timerMin', 'simulado'));
    }

    public function corrigirSimulado(Request $request)
    {
        $userId = Auth::id();

        $questionIds = $request->input('question_ids', []);
        $answers = $request->input('answers', []);
        $simuladoId = $request->input('simulado_id');

        $simulado = $simuladoId ? Simulado::find($simuladoId) : null;
        if (!$simulado && $userId) {
            // opcional: criar um registro fallback (não recomendado)
        }

        $erradas = [];
        $acertos = 0;
        $total = count($questionIds);

        foreach ($questionIds as $qid) {
            $q = Question::find($qid);
            if (!$q) continue;

            $given = isset($answers[$qid]) ? $answers[$qid] : null;
            $isCorrect = $q->isCorrectAnswer($given);

            if ($isCorrect) {
                $q->increment('correct_count');
                $acertos++;
            } else {
                $q->increment('wrong_count');
            }

            // salva resposta da tentativa se houver simulado
            if ($simulado) {
                SimuladoAnswer::create([
                    'simulado_id' => $simulado->id,
                    'question_id' => $q->id,
                    'given_answer' => $given,
                    'is_correct' => $isCorrect,
                ]);
            }

            if (!$isCorrect) {
                $erradas[] = [
                    'id' => $q->id,
                    'question' => $q->question_text,
                    'resposta' => $given ? (strtoupper($given) . ' - ' . ($q->options[$given] ?? '')) : 'Sem resposta',
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
                // duration_seconds pode ser passado do front; se vier:
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

        $questions = $query->inRandomOrder()->take($qtd)->get();

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

        $response = new StreamedResponse(function() use ($answers) {
            $handle = fopen('php://output', 'w');
            // cabeçalho
            fputcsv($handle, ['Question ID', 'Question Text', 'Given Answer', 'Correct Answer', 'Reason']);
            foreach ($answers as $a) {
                $q = $a->question;
                fputcsv($handle, [
                    $q->id,
                    strip_tags($q->question_text),
                    $a->given_answer,
                    strtoupper($q->correct_answer).' - '.($q->options[$q->correct_answer] ?? ''),
                    str_replace(["\r","\n"], ' ', strip_tags($q->reason))
                ]);
            }
            fclose($handle);
        });

        $filename = 'simulado_'.$simulado->id.'_erros.csv';
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        return $response;
    }
}
