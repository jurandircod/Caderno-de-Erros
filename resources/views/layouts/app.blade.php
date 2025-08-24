<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Caderno de Erros')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/4345/4345016.png">
    <style>
        /* Custom animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-slide-in {
            animation: slideIn 0.4s ease-out forwards;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
        }
    </style>


</head>

<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-blue-50 to-purple-50">
    <!-- Background decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div
            class="absolute top-0 -left-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse">
        </div>
        <div
            class="absolute top-0 -right-4 w-72 h-72 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse animation-delay-2000">
        </div>
        <div
            class="absolute -bottom-8 left-20 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse animation-delay-4000">
        </div>
    </div>

    <div class="relative z-10 container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header modernizado -->
            <header class="text-center mb-12 animate-fade-in-up">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl mb-6 shadow-lg">
                    <i class="fas fa-book-open text-white text-2xl"></i>
                </div>
                <h1 class="text-5xl font-bold gradient-text mb-4">Caderno de Erros</h1>
                <p class="text-xl text-gray-600 font-medium">Sistema Inteligente de Estudos com Questões</p>
            </header>

            <!-- Navigation modernizada -->
            <nav class="mb-12 animate-fade-in-up" style="animation-delay: 0.2s">
                <div class="flex justify-center">
                    <div
                        class="bg-white/80 backdrop-blur-lg rounded-3xl p-2 shadow-xl border border-white/20 max-w-4xl w-full">
                        <div class="grid grid-cols-3 sm:grid-cols-5 gap-1">
                            @if (Auth::check())
                                <a href="{{ route('quiz') }}"
                                    class="nav-item group {{ Route::is('quiz') ? 'active' : '' }}">
                                    <div
                                        class="flex flex-col items-center p-2 rounded-xl transition-all duration-200 group-hover:bg-gradient-to-r group-hover:from-indigo-500 group-hover:to-purple-600 group-hover:text-white group-hover:shadow-md group-hover:scale-103">
                                        <i
                                            class="fas fa-brain text-lg mb-1 transition-transform duration-200 group-hover:scale-105"></i>
                                        <span class="font-semibold text-xs">Estudar</span>
                                    </div>
                                </a>

                                <a href="{{ route('questions.create') }}"
                                    class="nav-item group {{ Route::is('questions.create') ? 'active' : '' }}">
                                    <div
                                        class="flex flex-col items-center p-2 rounded-xl transition-all duration-200 group-hover:bg-gradient-to-r group-hover:from-emerald-500 group-hover:to-teal-600 group-hover:text-white group-hover:shadow-md group-hover:scale-103">
                                        <i
                                            class="fas fa-plus-circle text-lg mb-1 transition-transform duration-200 group-hover:scale-105"></i>
                                        <span class="font-semibold text-xs">Nova Questão</span>
                                    </div>
                                </a>

                                <a href="{{ route('questions.delete') }}" class="nav-item group">
                                    <div
                                        class="flex flex-col items-center p-2 rounded-xl transition-all duration-200 group-hover:bg-gradient-to-r group-hover:from-red-500 group-hover:to-pink-600 group-hover:text-white group-hover:shadow-md group-hover:scale-103">
                                        <i
                                            class="fas fa-trash-alt text-lg mb-1 transition-transform duration-200 group-hover:scale-105"></i>
                                        <span class="font-semibold text-xs">Excluir</span>
                                    </div>
                                </a>

                                <a href="{{ route('categories.index') }}"
                                    class="nav-item group {{ Route::is('categories.index') ? 'active' : '' }}">
                                    <div
                                        class="flex flex-col items-center p-2 rounded-xl transition-all duration-200 group-hover:bg-gradient-to-r group-hover:from-orange-500 group-hover:to-yellow-600 group-hover:text-white group-hover:shadow-md group-hover:scale-103">
                                        <i
                                            class="fas fa-tags text-lg mb-1 transition-transform duration-200 group-hover:scale-105"></i>
                                        <span class="font-semibold text-xs">Categorias</span>
                                    </div>
                                </a>

                                <a href="{{ route('questions.stats') }}"
                                    class="nav-item group {{ Route::is('questions.stats') ? 'active' : '' }}">
                                    <div
                                        class="flex flex-col items-center p-2 rounded-xl transition-all duration-200 group-hover:bg-gradient-to-r group-hover:from-cyan-500 group-hover:to-blue-600 group-hover:text-white group-hover:shadow-md group-hover:scale-103">
                                        <i
                                            class="fas fa-chart-bar text-lg mb-1 transition-transform duration-200 group-hover:scale-105"></i>
                                        <span class="font-semibold text-xs">Estatísticas</span>
                                    </div>
                                </a>

                                <a href="{{ route('logout') }}" class="nav-item group">
                                    <div
                                        class="flex flex-col items-center p-2 rounded-xl transition-all duration-200 group-hover:bg-gradient-to-r group-hover:from-gray-600 group-hover:to-gray-800 group-hover:text-white group-hover:shadow-md group-hover:scale-103">
                                        <i
                                            class="fas fa-sign-out-alt text-lg mb-1 transition-transform duration-200 group-hover:scale-105"></i>
                                        <span class="font-semibold text-xs">Sair</span>
                                    </div>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="nav-item group active">
                                    <div
                                        class="flex flex-col items-center p-2 rounded-xl transition-all duration-200 group-hover:bg-gradient-to-r group-hover:from-indigo-500 group-hover:to-purple-600 group-hover:text-white group-hover:shadow-md group-hover:scale-103">
                                        <i
                                            class="fas fa-sign-in-alt text-lg mb-1 transition-transform duration-200 group-hover:scale-105"></i>
                                        <span class="font-semibold text-xs">Login</span>
                                    </div>
                                </a>

                                <a href="{{ route('register') }}" class="nav-item group active">
                                    <div
                                        class="flex flex-col items-center p-2 rounded-xl transition-all duration-200 group-hover:bg-gradient-to-r group-hover:from-green-500 group-hover:to-teal-600 group-hover:text-white group-hover:shadow-md group-hover:scale-103">
                                        <i
                                            class="fas fa-user-plus text-lg mb-1 transition-transform duration-200 group-hover:scale-105"></i>
                                        <span class="font-semibold text-xs">Registrar</span>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </nav>


            <!-- Alertas modernizados -->
            @if (session('success'))
                <div class="mb-8 animate-slide-in">
                    <div
                        class="bg-gradient-to-r from-emerald-50 to-teal-50 border-l-4 border-emerald-500 rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                            </div>
                            <div class="ml-auto">
                                <button type="button" class="text-emerald-400 hover:text-emerald-600 transition-colors"
                                    data-dismiss="alert">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-8 animate-slide-in">
                    <div
                        class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 rounded-2xl p-6 shadow-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <ul class="space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-red-800 font-medium">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="ml-auto">
                                <button type="button" class="text-red-400 hover:text-red-600 transition-colors"
                                    data-dismiss="alert">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif



            @yield('content')
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>
