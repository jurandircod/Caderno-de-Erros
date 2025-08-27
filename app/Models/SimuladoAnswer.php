<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SimuladoAnswer extends Model
{
    use HasFactory;

    protected $table = 'simulado_answers';

    protected $fillable = [
        'simulado_id','question_id','given_answer','is_correct'
    ];

    public function question()
    {
        return $this->belongsTo(\App\Models\Question::class);
    }

    public function simulado()
    {
        return $this->belongsTo(Simulado::class);
    }
}
