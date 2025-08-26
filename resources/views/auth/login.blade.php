@extends('layouts.app')

@section('title', 'Login - Caderno de Erros')

@section('content')
    <div class="min-h-screen flex items-center justify-center from-gray-50 to-gray-100 py-16">
        <div class="max-w-3xl w-full grid grid-cols-1 md:grid-cols-2 gap-8 px-6">
            <!-- Left: Branding / Promo -->
            <div
                class="hidden md:flex flex-col justify-center rounded-3xl p-10 bg-gradient-to-br from-indigo-600 to-purple-700 text-white shadow-2xl">
                <div class="mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 rounded-xl mb-4">
                        <i class="fas fa-book-open text-white text-2xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold">Caderno de Erros</h2>
                    <p class="mt-2 text-indigo-100/90">Revisões inteligentes para você aprender com seus erros. Acesse sua
                        conta e continue estudando.</p>
                </div>

                <div class="mt-auto">
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <div class="font-semibold">Revisões por categoria</div>
                                <div class="text-indigo-100/80 text-sm">Veja onde errar com mais frequência.</div>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-line text-white"></i>
                            </div>
                            <div>
                                <div class="font-semibold">Estatísticas detalhadas</div>
                                <div class="text-indigo-100/80 text-sm">Acompanhe seu progresso com gráficos.</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right: Login form -->
            <div class="bg-white rounded-3xl p-8 shadow-2xl border border-white/30">
                <div class="mb-6 text-center">
                    <div
                        class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-xl mx-auto mb-4 shadow-lg">
                        <i class="fas fa-user-lock text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Entrar na sua conta</h3>
                    <p class="text-sm text-gray-500 mt-1">Use seu e-mail e senha para acessar o Caderno de Erros.</p>
                </div>

                <!-- Session / Validation messages -->
                @if (session('status'))
                    <div class="mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-100 text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition"
                            placeholder="seu@exemplo.com">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Senha</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required
                                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition pr-12"
                                placeholder="••••••••">
                            <button type="button" id="togglePassword" aria-label="Mostrar senha"
                                class="absolute right-2 top-2/4 -translate-y-2/4 px-3 py-2 rounded-md text-gray-500 hover:text-gray-700 transition">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember / Forgot -->
                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="form-checkbox h-4 w-4 text-indigo-600 rounded"
                                {{ old('remember') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700 select-none">Lembrar-me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-indigo-600 hover:underline">Esqueceu a senha?</a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-3 px-6 py-3 rounded-2xl font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-700 hover:scale-[1.02] transition-shadow shadow-lg">
                            <i class="fas fa-sign-in-alt"></i>
                            Entrar
                        </button>
                    </div>

                    <!-- Social / Divider -->
                    <div class="flex items-center gap-3">
                        <hr class="flex-1 border-gray-200">
                        <span class="text-sm text-gray-400">ou</span>
                        <hr class="flex-1 border-gray-200">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <!-- Example social buttons (configure routes if needed) -->
                        <a href="{{ url('/auth/google') }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl border border-gray-200 hover:shadow-sm transition">
                            <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" class="w-4 h-4">
                            <span class="text-sm text-gray-700">Google</span>
                        </a>
                    </div>

                    <!-- Register link -->
                    <p class="text-sm text-center text-gray-500 mt-2">
                        Não tem conta?
                        <a href="{{ route('register') }}" class="text-indigo-600 font-medium hover:underline">Crie uma
                            agora</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Mostrar/ocultar senha
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            if (toggle && password) {
                toggle.addEventListener('click', function() {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }

            // Ensure all buttons default to type="button" (avoid accidental form submit)
            document.querySelectorAll('button').forEach(btn => {
                if (!btn.hasAttribute('type')) btn.setAttribute('type', 'button');
            });

            // Allow submit with Enter in inputs
            const form = document.querySelector('form');
            form.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    // If focus is on an input and not on a button, submit the form
                    if (document.activeElement.tagName === 'INPUT') {
                        // find submit button and click it
                        const submit = form.querySelector('button[type="submit"]');
                        if (submit) submit.click();
                    }
                }
            });
        });
    </script>
@endsection
