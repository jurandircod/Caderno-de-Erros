<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Category;

class QuestionController extends Controller
{
    public function create()
    {
        return view('questions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:a,b,c,d',
            'reason' => 'required|string',
            'category_id' => 'required|exists:categories,id', // 🔹 nova validação
        ]);

        $question = Question::create([
            'question_text' => $request->question_text,
            'options' => [
                'a' => $request->option_a,
                'b' => $request->option_b,
                'c' => $request->option_c,
                'd' => $request->option_d
            ],
            'correct_answer' => $request->correct_answer,
            'reason' => $request->reason,
            'category_id' => $request->category_id, // 🔹 salva categoria
        ]);
        return redirect()->back()->with('success', 'Questão criada com sucesso!');
    }

    public function indexDelete()
    {
        $questions = Question::with('category')->get();
        return view('questions.delete', compact('questions'));
    }


    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->route('questions.delete')->with('success', 'Questão excluída com sucesso!');
    }


    public function quiz(Request $request)
    {
        $categories = Category::all();
        $selectedCategories = $request->input('categories', []);

        $question = Question::getRandomQuestion($selectedCategories);

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
            'answer' => 'required|string'
        ]);

        $question = Question::findOrFail($request->question_id);
        $isCorrect = $question->isCorrectAnswer($request->answer);

        if ($isCorrect) {
            $question->increment('correct_count');
        } else {
            $question->increment('wrong_count');
        }

        return response()->json([
            'correct' => $isCorrect,
            'correct_answer' => $question->correct_answer,
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
        $categories = Category::all();
        $selectedCategories = $request->input('categories', []);

        $mostWrong = Question::with('category')
            ->when($selectedCategories, fn($q) => $q->whereIn('category_id', $selectedCategories))
            ->where('wrong_count', '>', 0)
            ->orderByDesc('wrong_count')
            ->get();

        $mostCorrect = Question::with('category')
            ->when($selectedCategories, fn($q) => $q->whereIn('category_id', $selectedCategories))
            ->where('correct_count', '>', 0)
            ->orderByDesc('correct_count')
            ->get();

        return view('questions.stats', compact('mostWrong', 'mostCorrect', 'categories', 'selectedCategories'));
    }
}
