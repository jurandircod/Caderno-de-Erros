<?php

use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [QuestionController::class, 'quiz'])->name('quiz');
Route::get('/quiz', [QuestionController::class, 'quiz'])->name('quiz');
Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
Route::post('/quiz/check', [QuestionController::class, 'checkAnswer'])->name('quiz.check');
Route::get('/api/question/random', [QuestionController::class, 'getRandomQuestion'])->name('api.question.random');
Route::get('/questions/delete', [QuestionController::class, 'indexDelete'])->name('questions.delete');
Route::delete('/questions/{id}', [QuestionController::class, 'destroy'])->name('questions.destroy');


use App\Http\Controllers\CategoryController;

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

Route::get('/questions/stats', [QuestionController::class, 'stats'])->name('questions.stats');
