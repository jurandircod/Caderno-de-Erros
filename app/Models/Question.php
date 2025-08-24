<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'options',
        'correct_answer',
        'reason'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public static function getRandomQuestion($categoryIds = [])
    {
        $query = self::query();

        // Filtra por categorias se forem selecionadas
        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        $questions = $query->get();

        if ($questions->isEmpty()) {
            return null;
        }

        // Calcula peso baseado em erros (mais errado = mais chance)
        $weighted = $questions->flatMap(function ($q) {
            $weight = max(1, $q->wrong_count - $q->correct_count + 1);
            return array_fill(0, $weight, $q);
        });

        return $weighted->random();
    }



    public function isCorrectAnswer($answer)
    {
        return strtolower($answer) === strtolower($this->correct_answer);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
