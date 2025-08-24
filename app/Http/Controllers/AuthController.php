<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
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
            'email' => ['required','email'],
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
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')],
            'password' => ['required','string','min:8','confirmed'],
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

    /**
     * Redirecionar para provedor social (placeholder).
     * Você pode implementar Socialite aqui se quiser.
     */
    public function redirectToProvider($provider)
    {
        // Exemplo: se quiser usar Laravel Socialite, aqui redireciona:
        // return Socialite::driver($provider)->redirect();

        return redirect()->route('login')->with('error', "Login via {$provider} não está configurado.");
    }

    /**
     * Callback do provedor social (placeholder).
     */
    public function handleProviderCallback($provider)
    {
        // Aqui você trataria o callback do provedor via Socialite
        return redirect()->route('login')->with('error', "Callback do {$provider} não implementado.");
    }
}
