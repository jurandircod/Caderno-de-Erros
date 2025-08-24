@extends('layouts.app')

@section('title', 'Registrar - Caderno de Erros')

@section('content')
<div class="min-h-screen flex items-center justify-center from-gray-50 to-gray-100 py-16">
    <div class="max-w-3xl w-full grid grid-cols-1 md:grid-cols-2 gap-8 px-6">
        <!-- Left: Branding / Promo -->
        <div class="hidden md:flex flex-col justify-center rounded-3xl p-10 bg-gradient-to-br from-indigo-600 to-purple-700 text-white shadow-2xl">
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 rounded-xl mb-4">
                    <i class="fas fa-book-open text-white text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold">Caderno de Erros</h2>
                <p class="mt-2 text-indigo-100/90">Registre-se e comece a transformar erros em aprendizado.</p>
            </div>

            <div class="mt-auto">
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-white/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div>
                            <div class="font-semibold">Revisões por categoria</div>
                            <div class="text-indigo-100/80 text-sm">Veja onde você erra com mais frequência.</div>
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

        <!-- Right: Register form -->
        <div class="bg-white rounded-3xl p-8 shadow-2xl border border-white/30">
            <div class="mb-6 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-xl mx-auto mb-4 shadow-lg">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Criar nova conta</h3>
                <p class="text-sm text-gray-500 mt-1">Preencha os dados abaixo para começar.</p>
            </div>

            <!-- Session / Validation messages -->
            @if(session('status'))
                <div class="mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-100 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="space-y-6" novalidate>
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                           class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition"
                           placeholder="Seu nome completo">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
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
                               placeholder="Mínimo 8 caracteres">
                        <button type="button" data-toggle="password" data-target="password" aria-label="Mostrar senha"
                                class="absolute right-2 top-2/4 -translate-y-2/4 px-3 py-2 rounded-md text-gray-500 hover:text-gray-700 transition">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmar senha</label>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition pr-12"
                               placeholder="Repita sua senha">
                        <button type="button" data-toggle="password" data-target="password_confirmation" aria-label="Mostrar senha"
                                class="absolute right-2 top-2/4 -translate-y-2/4 px-3 py-2 rounded-md text-gray-500 hover:text-gray-700 transition">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-3 px-6 py-3 rounded-2xl font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-700 hover:scale-[1.02] transition-shadow shadow-lg">
                        <i class="fas fa-user-plus"></i>
                        Criar Conta
                    </button>
                </div>

                <!-- Social / Divider -->
                <div class="flex items-center gap-3">
                    <hr class="flex-1 border-gray-200">
                    <span class="text-sm text-gray-400">ou</span>
                    <hr class="flex-1 border-gray-200">
                </div>

                <div class="grid grid-cols-2 gap-3 justify-center">
                    <a href="{{ route('login.provider', 'google') ?? '#' }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl border border-gray-200 hover:shadow-sm transition">
                        <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" class="w-4 h-4">
                        <span class="text-sm text-gray-700">Google</span>
                    </a>
                </div>

                <!-- Login link -->
                <p class="text-sm text-center text-gray-500 mt-2">
                    Já tem conta?
                    <a href="{{ route('login') }}" class="text-indigo-600 font-medium hover:underline">Entrar</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle password visibility for multiple targets
    document.querySelectorAll('[data-toggle="password"]').forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (!input) return;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            }
        });
        // ensure button doesn't submit forms
        if (!btn.hasAttribute('type')) btn.setAttribute('type', 'button');
    });

    // Ensure all other buttons default to type="button" to avoid accidental submits,
    // but keep the real submit button with type="submit"
    document.querySelectorAll('button').forEach(b => {
        if (!b.hasAttribute('type')) b.setAttribute('type', 'button');
    });

    // Simple client-side hints (optional): prevent submit if password < 8
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            const pw = document.getElementById('password');
            const pwc = document.getElementById('password_confirmation');
            if (pw && pw.value.length < 8) {
                e.preventDefault();
                alert('A senha precisa ter no mínimo 8 caracteres.');
                pw.focus();
                return false;
            }
            if (pw && pwc && pw.value !== pwc.value) {
                e.preventDefault();
                alert('As senhas não coincidem.');
                pwc.focus();
                return false;
            }
        });
    }
});
</script>
@endsection
