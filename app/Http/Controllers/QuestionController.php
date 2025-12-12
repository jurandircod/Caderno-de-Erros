<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Category;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use App\Http\Controllers\NotificationController;

class QuestionController extends Controller
{
    public function create()
    {
        return view('questions.create');
    }

    public function store(Request $request)
    {
        $userId = FacadesAuth::check() ? FacadesAuth::id() : null;
        if (!$userId) {
            return NotificationController::redirectWithNotification('login', 'Você precisa estar logado para criar questões.', 'error');
        }

        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'option_e' => 'nullable|string',
            'correct_answer' => 'required|in:a,b,c,d,e',
            'reason' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $options = [
            'a' => $request->option_a,
            'b' => $request->option_b,
            'c' => $request->option_c,
            'd' => $request->option_d,
        ];
        if ($request->filled('option_e')) {
            $options['e'] = $request->option_e;
        }

        $question = Question::create([
            'question_text' => $request->question_text,
            'options' => $options,
            'correct_answer' => $request->correct_answer,
            'reason' => $request->reason,
            'category_id' => $request->category_id,
            'user_id' => $userId,
        ]);

        return redirect()->back()->with('success', 'Questão criada com sucesso!');
    }

    public function indexDelete()
    {
        $userId = FacadesAuth::id();
        $questions = Question::with('category')->where('user_id', $userId)->get();
        $categories = Category::where('user_id', $userId)->get();
        return view('questions.delete', compact('questions', 'categories'));
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->route('questions.delete')->with('success', 'Questão excluída com sucesso!');
    }

    public function quiz(Request $request)
    {
        $userId = FacadesAuth::check() ? FacadesAuth::id() : null;
        if (!$userId) {
            return NotificationController::redirectWithNotification('login', 'Você precisa estar logado para acessar o quiz.', 'error');
        }

        $categories = Category::where('user_id', $userId)->get();
        $selectedCategories = $request->input('categories', []);
        $userId = FacadesAuth::check() ? FacadesAuth::id() : null;

        // busca questão (sem shuffle)
        $question = Question::getRandomQuestion($selectedCategories, $userId);
        

        if ($question) {
            // faz o shuffle aqui — e o objeto retornado terá correct_answer remapeado
            $question = $question->shuffled();
        }

        if (!$question) {
            return view('quiz', [
                'message' => 'Nenhuma questão cadastrada ainda.',
                'categories' => $categories,
                'selectedCategories' => $selectedCategories
            ]);
        }

        return view('quiz', [
            'question' => $question,
            'categories' => $categories,
            'selectedCategories' => $selectedCategories
        ]);
    }

    public function checkAnswer(Request $request): JsonResponse
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|string',
            'shuffled_correct' => 'nullable|string'
        ]);

        $question = Question::findOrFail($request->question_id);

        // Se o front enviou a alternativa correta remapeada (shuffled_correct), use-a.
        // Isto é necessário porque o objeto salvo no DB tem a alternativa original.
        $shuffledCorrect = $request->input('shuffled_correct', null);

        if ($shuffledCorrect !== null && $shuffledCorrect !== '') {
            $isCorrect = strtolower($request->answer) === strtolower($shuffledCorrect);
            $correctForClient = $shuffledCorrect;
        } else {
            $isCorrect = $question->isCorrectAnswer($request->answer);
            $correctForClient = $question->correct_answer;
        }

        if ($isCorrect) {
            $question->increment('correct_count');
        } else {
            $question->increment('wrong_count');
        }

        return response()->json([
            'correct' => $isCorrect,
            'correct_answer' => $correctForClient,
            'reason' => $question->reason
        ]);
    }

    public function getRandomQuestion(Request $request): JsonResponse
    {
        $categoryIds = $request->input('categories', []);
        $question = Question::getRandomQuestion($categoryIds);

        if (!$question) {
            return response()->json(['error' => 'Nenhuma questão disponível'], 404);
        }

        return response()->json($question);
    }

    public function stats(Request $request)
    {
        $userId = FacadesAuth::check() ? FacadesAuth::id() : null;
        if (!$userId) {
            return NotificationController::redirectWithNotification('login', 'Você precisa estar logado para acessar o quiz.', 'error');
        }

        $categories = Category::where('user_id', $userId)->get();
        $selectedCategories = $request->input('categories', []);

        $mostWrong = Question::with('category')
            ->when($selectedCategories, fn($q) => $q->whereIn('category_id', $selectedCategories))
            ->where('wrong_count', '>', 0)
            ->where('user_id', $userId)
            ->where('status', 'ativo')
            ->orderByDesc('wrong_count')
            ->get();

        $mostCorrect = Question::with('category')
            ->when($selectedCategories, fn($q) => $q->whereIn('category_id', $selectedCategories))
            ->where('correct_count', '>', 0)
            ->where('user_id', $userId)
            ->where('status', 'ativo')
            ->orderByDesc('correct_count')
            ->get();

        return view('questions.stats', compact('mostWrong', 'mostCorrect', 'categories', 'selectedCategories'));
    }

    public function update(Request $request, Question $question)
    {
        $userId = FacadesAuth::check() ? FacadesAuth::id() : null;
        if ($question->user_id && $question->user_id !== $userId) {
            return NotificationController::redirectWithNotification('quiz', 'Você não tem permissão para editar essa questão.', 'error');
        }


        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'option_e' => 'nullable|string',
            'correct_answer' => 'required|in:a,b,c,d,e',
            'reason' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $options = [
            'a' => $request->option_a,
            'b' => $request->option_b,
            'c' => $request->option_c,
            'd' => $request->option_d,
        ];
        if ($request->filled('option_e')) {
            $options['e'] = $request->option_e;
        }

        $question->update([
            'question_text' => $request->question_text,
            'options' => $options,
            'correct_answer' => $request->correct_answer,
            'reason' => $request->reason,
            'category_id' => $request->category_id,
        ]);

        return redirect()->back()->with('success', 'Questão atualizada com sucesso!');
    }

    public function cancel($id) {
 
        $question =  Question::where('id', $id);
        $question->update([
            "status" => 'desativado',
        ]);
        return redirect()->back()->with('success', 'Questão atualizada com sucesso');
    }
}
