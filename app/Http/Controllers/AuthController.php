<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{


    public function __construct()
    {
        session()->start(); // Garante que a sessão está iniciada
    }
    /**
     * Redirecionar para provedor (Google)
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->stateless()->user();
            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                    'email' => $socialUser->getEmail(),
                    'password' => bcrypt(Str::random(16)),
                ]);
            }

            Auth::login($user);

            return redirect()->intended('/');
        } catch (Exception $e) {
            Log::error('Full error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Erro de autenticação.');
        }
    }


    /**
     * Mostrar o formulário de login
     */
    public function showLoginForm()
    {
        return view('auth.login'); // certifique-se de que view existe em resources/views/auth/login.blade.php
    }

    /**
     * Tentar autenticar o usuário
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Regenera sessão para evitar fixation
            $request->session()->regenerate();

            return redirect()->intended(route('quiz'))
                ->with('success', 'Login realizado com sucesso.');
        }

        return back()->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'As credenciais fornecidas não correspondem aos nossos registros.']);
    }

    /**
     * Mostrar o formulário de registro
     */
    public function showRegisterForm()
    {
        return view('auth.register'); // se não tiver, crie resources/views/auth/register.blade.php
    }

    /**
     * Registrar novo usuário
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return redirect()->route('quiz')->with('success', 'Conta criada e você foi logado.');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Você saiu da sua conta.');
    }
}
