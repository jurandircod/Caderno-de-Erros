<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController; // <-- ADICIONE
use Illuminate\Support\Facades\Route;

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
    //categories
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });
    Route::get('/questions/stats', [QuestionController::class, 'stats'])->name('questions.stats');
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


