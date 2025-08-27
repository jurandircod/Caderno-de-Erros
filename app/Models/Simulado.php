<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Simulado extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','questions_count','correct_count','wrong_count','time_seconds','duration_seconds','categories'
    ];

    protected $casts = [
        'categories' => 'array',
    ];

    public function answers()
    {
        return $this->hasMany(SimuladoAnswer::class);
    }
}
