@extends('layouts.app')

@section('title', 'Nova Questão - Caderno de Erros')

@section('content')
    <div class="animate-fade-in-up">
        <!-- Page Header -->
        <div class="text-center mb-10">
            <div
                class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-3xl mb-6 shadow-xl">
                <i class="fas fa-plus-circle text-white text-2xl"></i>
            </div>
            <h2 class="text-4xl font-bold gradient-text mb-4">Criar Nova Questão</h2>
            <p class="text-xl text-gray-600">Adicione uma nova questão ao seu caderno de estudos</p>
        </div>

        <!-- Main Form Card -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 overflow-hidden animate-slide-in"
                style="animation-delay: 0.2s">
                <div class="p-8 md:p-12">
                    <form id="question-form" action="{{ route('questions.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <!-- Question Text Section -->
                        <div class="relative">
                            <label class="block text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <div
                                    class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-question text-white text-sm"></i>
                                </div>
                                Texto da Questão
                            </label>
                            <div class="relative">
                                <textarea id="question_text"
                                    class="w-full px-6 py-4 bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all duration-300 text-gray-800 placeholder-gray-400 font-medium text-lg resize-none @error('question_text') border-red-400 @enderror"
                                    name="question_text" rows="6" placeholder="Digite o enunciado da questão...">{{ old('question_text') }}</textarea>
                                <div class="absolute bottom-4 right-4">
                                    <i class="fas fa-edit text-blue-400 text-lg"></i>
                                </div>
                            </div>
                            @error('question_text')
                                <div class="mt-3 p-4 bg-red-50 border border-red-200 rounded-xl">
                                    <div class="text-red-600 font-semibold flex items-center">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        {{ $message }}
                                    </div>
                                </div>
                            @enderror
                            <div
                                class="mt-3 p-4 bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-xl">
                                <div class="flex items-start">
                                    <i class="fas fa-lightbulb text-yellow-500 text-lg mt-1 mr-3"></i>
                                    <div>
                                        <p class="text-yellow-800 font-semibold text-sm">💡 Dica Inteligente</p>
                                        <p class="text-yellow-700 text-sm mt-1">Você pode colar a questão inteira com as
                                            opções A), B), C), D) que o sistema separa automaticamente!</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Options Section -->
                        <div class="space-y-6">
                            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                                <div
                                    class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-list text-white text-sm"></i>
                                </div>
                                Opções de Resposta
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="relative group">
                                    <label class="block text-sm font-bold text-gray-700 mb-3">Opção A</label>
                                    <div class="relative">
                                        <input type="text" id="option_a"
                                            class="w-full px-6 py-4 bg-white border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 text-gray-800 placeholder-gray-400 font-medium group-hover:shadow-md @error('option_a') border-red-400 @enderror"
                                            name="option_a" value="{{ old('option_a') }}" placeholder="Digite a opção A">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-2">
                                            <span
                                                class="w-6 h-6 bg-purple-500 text-white rounded-full flex items-center justify-center text-xs font-bold">A</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative group">
                                    <label class="block text-sm font-bold text-gray-700 mb-3">Opção B</label>
                                    <div class="relative">
                                        <input type="text" id="option_b"
                                            class="w-full px-6 py-4 bg-white border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 text-gray-800 placeholder-gray-400 font-medium group-hover:shadow-md @error('option_b') border-red-400 @enderror"
                                            name="option_b" value="{{ old('option_b') }}" placeholder="Digite a opção B">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-2">
                                            <span
                                                class="w-6 h-6 bg-purple-500 text-white rounded-full flex items-center justify-center text-xs font-bold">B</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative group">
                                    <label class="block text-sm font-bold text-gray-700 mb-3">Opção C</label>
                                    <div class="relative">
                                        <input type="text" id="option_c"
                                            class="w-full px-6 py-4 bg-white border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 text-gray-800 placeholder-gray-400 font-medium group-hover:shadow-md @error('option_c') border-red-400 @enderror"
                                            name="option_c" value="{{ old('option_c') }}" placeholder="Digite a opção C">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-2">
                                            <span
                                                class="w-6 h-6 bg-purple-500 text-white rounded-full flex items-center justify-center text-xs font-bold">C</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative group">
                                    <label class="block text-sm font-bold text-gray-700 mb-3">Opção D</label>
                                    <div class="relative">
                                        <input type="text" id="option_d"
                                            class="w-full px-6 py-4 bg-white border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 text-gray-800 placeholder-gray-400 font-medium group-hover:shadow-md @error('option_d') border-red-400 @enderror"
                                            name="option_d" value="{{ old('option_d') }}" placeholder="Digite a opção D">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-2">
                                            <span
                                                class="w-6 h-6 bg-purple-500 text-white rounded-full flex items-center justify-center text-xs font-bold">D</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative group">
                                    <label class="block text-sm font-bold text-gray-700 mb-3">Opção E</label>
                                    <div class="relative">
                                        <input type="text" id="option_e"
                                            class="w-full px-6 py-4 bg-white border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 text-gray-800 placeholder-gray-400 font-medium group-hover:shadow-md @error('option_e') border-red-400 @enderror"
                                            name="option_e" value="{{ old('option_e') }}" placeholder="Digite a opção E">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-2">
                                            <span
                                                class="w-6 h-6 bg-purple-500 text-white rounded-full flex items-center justify-center text-xs font-bold">E</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Category and Correct Answer Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="relative">
                                <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-tags text-orange-500 mr-2"></i>
                                    Categoria
                                </label>
                                <div class="relative">
                                    <select name="category_id" id="category_id"
                                        class="w-full px-6 py-4 bg-white border-2 border-gray-200 rounded-xl focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 text-gray-800 font-medium appearance-none cursor-pointer hover:shadow-md"
                                        required>
                                        <option value="">-- Selecione uma Categoria --</option>
                                        @if ($categories = \App\Models\Category::where('user_id', Auth::id())->get())
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @else
                                            <option value="">Nenhuma categoria encontrada</option>
                                        @endif
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <i class="fas fa-chevron-down text-orange-400"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="relative">
                                <label class="block text-sm font-bold text-gray-700 mb-3 flex items-center">
                                    <i class="fas fa-check-circle text-emerald-500 mr-2"></i>
                                    Resposta Correta
                                </label>
                                <div class="relative">
                                    <select
                                        class="w-full px-6 py-4 bg-white border-2 border-gray-200 rounded-xl focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 transition-all duration-300 text-gray-800 font-medium appearance-none cursor-pointer hover:shadow-md @error('correct_answer') border-red-400 @enderror"
                                        name="correct_answer">
                                        <option value="">Selecione a resposta correta</option>
                                        <option value="a" {{ old('correct_answer') === 'a' ? 'selected' : '' }}>Opção
                                            A</option>
                                        <option value="b" {{ old('correct_answer') === 'b' ? 'selected' : '' }}>Opção
                                            B</option>
                                        <option value="c" {{ old('correct_answer') === 'c' ? 'selected' : '' }}>Opção
                                            C</option>
                                        <option value="d" {{ old('correct_answer') === 'd' ? 'selected' : '' }}>Opção
                                            D</option>
                                        <option value="e" {{ old('correct_answer') === 'e' ? 'selected' : '' }}>Opção
                                            E</option>

                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                        <i class="fas fa-chevron-down text-emerald-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Explanation Section -->
                        <div class="relative">
                            <label class="block text-lg font-bold text-gray-800 mb-4 flex items-center">
                                <div
                                    class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-lightbulb text-white text-sm"></i>
                                </div>
                                Explicação da Resposta
                            </label>
                            <div class="relative">
                                <textarea
                                    class="w-full px-6 py-4 bg-gradient-to-br from-cyan-50 to-blue-50 border-2 border-cyan-200 rounded-2xl focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 transition-all duration-300 text-gray-800 placeholder-gray-400 font-medium text-lg resize-none @error('reason') border-red-400 @enderror"
                                    name="reason" rows="4" placeholder="Explique por que esta é a resposta correta...">{{ old('reason') }}</textarea>
                                <div class="absolute bottom-4 right-4">
                                    <i class="fas fa-comment-dots text-cyan-400 text-lg"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap justify-center gap-6 pt-8">
                            <button type="submit"
                                class="px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl font-bold text-lg hover:shadow-xl hover:scale-105 transition-all duration-300 flex items-center space-x-3 shadow-lg min-w-[200px] justify-center">
                                <i class="fas fa-save"></i>
                                <span>Salvar Questão</span>
                            </button>
                            <a href="{{ route('quiz') }}"
                                class="px-8 py-4 bg-white text-gray-700 rounded-2xl font-bold text-lg border-2 border-gray-200 hover:bg-gray-50 hover:shadow-lg transition-all duration-300 flex items-center space-x-3 min-w-[200px] justify-center hover:scale-105">
                                <i class="fas fa-arrow-left"></i>
                                <span>Voltar ao Quiz</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Custom select styling */
        select {
            background-image: none;
        }

        /* Focus styles */
        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
        }
    </style>
@endsection
@section('scripts')
    <script>
        document.getElementById("question_text").addEventListener("paste", function(e) {
            // aguarda um pouco porque o valor do textarea só fica disponível após o paste
            setTimeout(() => {
                let text = e.target.value;

                // Regex atualizado: aceita A) ou A. (maiúsculo ou minúsculo)
                let regex =
                    /(.*?)\s*[Aa][\)\.](.*?)\s*[Bb][\)\.](.*?)\s*[Cc][\)\.](.*?)\s*[Dd][\)\.](.*?)\s*[Ee][\)\.](.*)/s;
                let match = text.match(regex);

                if (match) {
                    // match[1] = enunciado
                    // match[2] = opção A
                    // match[3] = opção B
                    // match[4] = opção C
                    // match[5] = opção D

                    document.getElementById("question_text").value = match[1].trim();
                    document.getElementById("option_a").value = match[2].trim();
                    document.getElementById("option_b").value = match[3].trim();
                    document.getElementById("option_c").value = match[4].trim();
                    document.getElementById("option_d").value = match[5].trim();
                    document.getElementById("option_e").value = match[6].trim();


                    // Add visual feedback
                    const inputs = [
                        document.getElementById("option_a"),
                        document.getElementById("option_b"),
                        document.getElementById("option_c"),
                        document.getElementById("option_d"),
                        document.getElementById("option_e")
                    ];


                    inputs.forEach((input, index) => {
                        setTimeout(() => {
                            input.style.transform = 'scale(1.05)';
                            input.style.boxShadow = '0 4px 20px rgba(139, 92, 246, 0.3)';
                            setTimeout(() => {
                                input.style.transform = 'scale(1)';
                                input.style.boxShadow = '';
                            }, 300);
                        }, index * 100);
                    });
                }
            }, 50);
        });
    </script>
@endsection
