<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController; // <-- ADICIONE
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;

/*
|-----------------------------------------------------------------------
| Rotas do Quiz / Questions / Categories (suas existentes)
|-----------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    //questions
    Route::get('/', [QuestionController::class, 'quiz'])->name('quiz');
    Route::get('/quiz', [QuestionController::class, 'quiz'])->name('quiz');
    Route::post('/quiz/check', [QuestionController::class, 'checkAnswer'])->name('quiz.check');
    Route::get('/api/question/random', [QuestionController::class, 'getRandomQuestion'])->name('api.question.random');
    Route::get('/questions/delete', [QuestionController::class, 'indexDelete'])->name('questions.delete');
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    Route::get('/questions/{id}/cancel', [QuestionController::class, 'cancel'])->name('questions.cancel');
    //categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::patch('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // questions (já existem store/destroy etc. adicionamos update)
    Route::patch('/questions/{question}', [\App\Http\Controllers\QuestionController::class, 'update'])->name('questions.update');
    Route::get('/questions/stats', [QuestionController::class, 'stats'])->name('questions.stats');

    Route::get('/quiz', [\App\Http\Controllers\QuestionController::class, 'quiz'])->name('quiz');

    Route::get('/quiz/simulado', [QuizController::class, 'simulado'])->name('quiz.simulado');
    Route::post('/quiz/simulado/corrigir', [QuizController::class, 'corrigirSimulado'])->name('quiz.simulado.corrigir');

    // rota para refazer com mesmos filtros (pode apenas redirecionar para simulado)
    Route::get('/quiz/simulado/refazer/{simulado}', [QuizController::class, 'refazer'])->name('quiz.simulado.refazer');

    // exportar erradas (CSV) de uma tentativa
    Route::get('/quiz/simulado/{simulado}/export-erros', [QuizController::class, 'exportErradas'])->name('quiz.simulado.export');
});




/*
|-----------------------------------------------------------------------
| Rotas de Autenticação (login / register / logout / social)
|-----------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout'); // suporte GET para logout via link

// Social (placeholders para os botões Google / GitHub)
Route::get('auth/google', [AuthController::class, 'redirectToProvider'])->name('google.redirect');
Route::get('auth/google/callback', [AuthController::class, 'handleProviderCallback'])->name('google.callback');

Route::get('/auth/{provider}', [AuthController::class, 'redirectToProvider'])->name('login.provider');
Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('login.provider.callback');
