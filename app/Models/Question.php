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
        'reason',
        'category_id',
        'user_id',
        'status',
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retorna uma questão aleatória (não embaralhada).
     * O embaralhamento fica por conta do controller (chame ->shuffled()).
     */
    public static function getRandomQuestion($categoryIds = [], $userId = null)
    {
        $query = self::query();

        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }
        
        $query->where('correct_count', '<=', 4);
        $query->where('status', 'ativo');

        if (!empty($userId)) {
            $userId = is_numeric($userId) ? (int)$userId : null;
            $query->where(function ($q) use ($userId) {
                $q->whereNull('user_id')->orWhere('user_id', $userId);
            });
        } else {
            $query->whereNull('user_id');
        }

        $questions = $query->get();

        if ($questions->isEmpty()) {
            return null;
        }

        // peso baseado em erros (mais errado = maior chance)
        $weighted = $questions->flatMap(function ($q) {
            $weight = max(1, $q->wrong_count - $q->correct_count + 1);
            return array_fill(0, $weight, $q);
        });

        // retorna apenas um modelo (sem embaralhar)
        return $weighted->random();
    }



    /**
     * Retorna uma cópia da questão com as opções embaralhadas
     * e com a resposta correta remapeada para a nova chave.
     *
     * Faz o shuffle preservando pares (oldKey => value), assim podemos
     * identificar qual par continha a key correta original e atribuir a nova key.
     */
    public function shuffled()
    {
        $options = $this->options ?? [];
        if (empty($options) || !is_array($options)) {
            return $this;
        }
        // chave correta original (ex: 'a')
        $originalCorrectKey = $this->correct_answer;

        // transforma em lista de pares [ ['k'=>'a','v'=>'texto'], ... ]
        $pairs = [];
        foreach ($options as $k => $v) {
            $pairs[] = ['k' => $k, 'v' => $v];
        }

        // embaralha os pares preservando cada par (chave original ainda disponível em 'k')
        shuffle($pairs);

        // mapear para novas chaves 'a','b','c'...
        $letters = range('a', 'z');
        $mapped = [];
        $newCorrect = null;
        $i = 0;

        foreach ($pairs as $pair) {
            $oldKey = $pair['k'];
            $value = $pair['v'];
            $newKey = $letters[$i];
            $mapped[$newKey] = $value;

            // se o oldKey era a resposta correta original, setar a nova key como correta
            if ($oldKey === $originalCorrectKey) {
                $newCorrect = $newKey;
            }

            $i++;
        }

        // se por alguma razão não encontramos (defensivo), deixamos a mesma
        if (is_null($newCorrect)) {
            // fallback: procurar pelo mesmo valor (caso user tenha mudado a estrutura)
            foreach ($mapped as $k => $v) {
                if ($v === ($options[$originalCorrectKey] ?? null)) {
                    $newCorrect = $k;
                    break;
                }
            }
        }

        // clona sem salvar no banco
        $clone = clone $this;
        $clone->options = $mapped;
        // se newCorrect for nulo, mantemos a original (menos provável)
        $clone->correct_answer = $newCorrect ?? $this->correct_answer;

        return $clone;
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
