@extends('layouts.app')

@section('title', 'Quiz - Caderno de Erros')

@section('content')
    <div class="animate-fade-in-up max-w-6xl mx-auto">

        {{-- Filtro de Categorias --}}
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-3xl p-8 border border-purple-100 mb-10 shadow-xl animate-slide-in"
            style="animation-delay: 0.1s">
            <div class="flex items-center mb-6">
                <div
                    class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-filter text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Filtrar por Categorias</h3>
            </div>

            <form method="GET" action="{{ route('quiz') }}" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach ($categories as $category)
                        <div class="relative">
                            <input class="sr-only peer" type="checkbox" name="categories[]" value="{{ $category->id }}"
                                id="cat{{ $category->id }}"
                                {{ in_array($category->id, $selectedCategories ?? []) ? 'checked' : '' }}>
                            <label
                                class="flex items-center p-4 bg-white border-2 border-gray-200 rounded-xl cursor-pointer hover:shadow-md transition-all duration-300 peer-checked:border-purple-500 peer-checked:bg-gradient-to-r peer-checked:from-purple-50 peer-checked:to-pink-50 peer-checked:shadow-lg group"
                                for="cat{{ $category->id }}">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-6 h-6 border-2 border-gray-300 rounded-lg flex items-center justify-center peer-checked:border-purple-500 peer-checked:bg-purple-500 group-hover:border-purple-400 transition-all duration-300">
                                        <i
                                            class="fas fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity duration-300"></i>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-tag text-purple-400"></i>
                                        <span
                                            class="font-semibold text-gray-700 group-hover:text-purple-600 peer-checked:text-purple-700">{{ $category->name }}</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-center">
                    <button type="submit"
                        class="px-8 py-4 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-2xl font-bold text-lg hover:shadow-xl hover:scale-105 transition-all duration-300 flex items-center space-x-3 shadow-lg">
                        <i class="fas fa-filter"></i>
                        <span>Aplicar Filtro</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Mini Simulado -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl p-8 border border-indigo-100 mb-10 shadow-xl animate-slide-in"
            style="animation-delay: 0.15s">
            <div class="flex items-center mb-6">
                <div
                    class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-blue-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-stopwatch text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">Mini Simulado</h3>
            </div>

            <form method="GET" action="{{ route('quiz.simulado') }}" class="flex flex-wrap gap-4 justify-center">
                <select name="qtd"
                    class="px-6 py-3 rounded-xl border border-indigo-200 text-lg font-semibold shadow-sm">
                    <option value="5">5 questões</option>
                    <option value="10">10 questões</option>
                    <option value="15">15 questões</option>
                    <option value="20">20 questões</option>
                    <option value="40">40 questões</option>
                    <option value="60">60 questões</option>
                </select>

                <select name="timer" class="px-4 py-2 rounded-xl border">
                    <option value="10">10 min</option>
                    <option value="20" selected>20 min</option>
                    <option value="30">30 min</option>
                    <option value="60">60 min</option>
                </select>

                {{-- manter categorias selecionadas em hidden inputs --}}
                @foreach ((array) ($selectedCategories ?? []) as $catId)
                    <input type="hidden" name="categories[]" value="{{ $catId }}">
                @endforeach

                <button type="submit"
                    class="px-8 py-4 bg-gradient-to-r from-indigo-500 to-blue-600 text-white rounded-2xl font-bold text-lg shadow-lg">
                    Iniciar Simulado
                </button>
            </form>

        </div>

        <!-- Question Card -->
        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 overflow-hidden animate-fade-in-up"
            style="animation-delay: 0.2s">
            <div class="p-8 md:p-12">
                @if (isset($message))
                    <!-- Empty State -->
                    <div class="text-center py-16">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                            <i class="fas fa-book-open text-blue-500 text-3xl"></i>
                        </div>
                        <h4 class="text-3xl font-bold text-gray-800 mb-4">{{ $message }}</h4>
                        <p class="text-xl text-gray-600 mb-8">Comece criando sua primeira questão para começar a estudar!
                        </p>
                        <a href="{{ route('questions.create') }}"
                            class="px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl font-bold text-lg hover:shadow-xl hover:scale-105 transition-all duration-300 inline-flex items-center space-x-3 shadow-lg">
                            <i class="fas fa-plus"></i>
                            <span>Criar Primeira Questão</span>
                        </a>
                    </div>
                @else
                    <!-- Category Badge -->
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-r from-orange-500 to-yellow-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-folder text-white"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Categoria</p>
                                <p class="text-lg font-bold text-gray-800">
                                    {{ $question->category->name ?? 'Não definida' }}
                                </p>
                            </div>
                        </div>
                        <span
                            class="px-4 py-2 bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 rounded-full text-sm font-bold border border-blue-200">
                            <i class="fas fa-question-circle mr-1"></i>
                           <a href="/questions/{{$question->id}}/cancel">Cancelar Questão</a>
                        </span>
                    </div>

                    <div id="question-container">
                        <!-- Question Text -->
                        <div
                            class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-8 mb-8 border border-indigo-100">
                            <h4 class="text-2xl font-bold text-gray-800 leading-relaxed" id="question-text">
                                {{ $question->question_text }}
                            </h4>
                        </div>

                        <!-- Options -->
                        <div id="options-container" class="space-y-4 mb-8">
                            @foreach ($question->options as $key => $option)
                                <button
                                    class="option-btn group w-full p-6 bg-white hover:bg-gradient-to-r hover:from-indigo-500 hover:to-purple-600 rounded-2xl border-2 border-gray-200 hover:border-transparent text-left transition-all duration-300 hover:text-white hover:shadow-xl hover:scale-[1.02] focus:outline-none focus:ring-4 focus:ring-indigo-100"
                                    data-answer="{{ $key }}">
                                    <div class="flex items-center">
                                        <span
                                            class="w-8 h-8 bg-gradient-to-r from-gray-100 to-gray-200 group-hover:from-white/20 group-hover:to-white/20 rounded-full flex items-center justify-center text-sm font-bold mr-6 transition-all duration-300 shadow-sm">
                                            {{ strtoupper($key) }}
                                        </span>
                                        <span class="font-semibold text-lg leading-relaxed">{{ $option }}</span>
                                    </div>
                                </button>
                            @endforeach
                        </div>

                        <!-- Explanation Box -->
                        <div id="reason-container"
                            class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-8 border-l-4 border-blue-500 shadow-lg"
                            style="display: none;">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4">
                                        <i class="fas fa-lightbulb text-white text-lg"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="font-bold text-blue-800 text-lg mb-3 flex items-center">
                                        <i class="fas fa-graduation-cap mr-2"></i>
                                        Explicação
                                    </h6>
                                    <p id="reason-text" class="text-blue-700 leading-relaxed text-lg">
                                        {{ $question->reason }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap justify-center gap-6 mt-10">
                            <button id="show-reason-btn"
                                class="px-8 py-4 bg-gradient-to-r from-cyan-500 to-blue-600 text-white rounded-2xl font-bold text-lg hover:shadow-xl hover:scale-105 transition-all duration-300 flex items-center space-x-3 shadow-lg"
                                onclick="showReason()" style="display:none;">
                                <i class="fas fa-eye"></i>
                                <span>Mostrar Explicação</span>
                            </button>
                            <button id="next-question-btn"
                                class="px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl font-bold text-lg hover:shadow-xl hover:scale-105 transition-all duration-300 flex items-center space-x-3 shadow-lg"
                                onclick="nextQuestion()">
                                <i class="fas fa-forward"></i>
                                <span>Próxima Questão</span>
                            </button>
                        </div>
                    </div>

                    <!-- Hidden inputs: id da questão e alternativa correta após embaralhar -->
                    <input type="hidden" id="current-question-id" value="{{ $question->id }}">
                    <input type="hidden" id="current-correct-answer" value="{{ $question->correct_answer }}">
                @endif
            </div>
        </div>
    </div>

    <style>
    </style>
@endsection

@section('scripts')
    <script>
        let answered = false;

        // (re)bind - garante que existam listeners
        function bindOptionButtons() {
            document.querySelectorAll('.option-btn').forEach(btn => {
                // evitar duplicação de listeners
                btn.removeEventListener('click', optionClickHandler);
                btn.addEventListener('click', optionClickHandler);
            });
        }

        function optionClickHandler(evt) {
            checkAnswer(evt.currentTarget);
        }

        bindOptionButtons();

        function checkAnswer(button) {
            if (answered) return;
            answered = true;

            const selectedAnswer = button.getAttribute('data-answer');
            const questionId = document.getElementById('current-question-id').value;
            const shuffledCorrect = document.getElementById('current-correct-answer').value;

            // desabilita botões
            document.querySelectorAll('.option-btn').forEach(b => b.disabled = true);

            // Marca imediatamente no front usando a alternativa embaralhada
            document.querySelectorAll('.option-btn').forEach(b => {
                const opt = b.getAttribute('data-answer');
                if (opt === shuffledCorrect) {
                    b.classList.add('btn-success');
                    // evita duplicar ícone
                    if (!b.querySelector('.fa-check')) b.innerHTML += ' <i class="fas fa-check ml-auto"></i>';
                }
                if (opt === selectedAnswer && opt !== shuffledCorrect) {
                    b.classList.add('btn-danger');
                    if (!b.querySelector('.fa-times')) b.innerHTML += ' <i class="fas fa-times ml-auto"></i>';
                }
            });

            document.getElementById('show-reason-btn').style.display = 'inline-flex';

            // envia o registro ao backend (inclui shuffled_correct para o backend validar da mesma forma)
            fetch('{{ route('quiz.check') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        question_id: questionId,
                        answer: selectedAnswer,
                        shuffled_correct: shuffledCorrect
                    })
                })
                .then(res => res.json())
                .then(data => {
                    // opcional: reconciliar se backend discordar (por segurança)
                    // neste fluxo o front já exibiu resposta, mas podemos atualizar reason/correct_answer com retorno.
                    if (data && data.reason) {
                        document.getElementById('reason-text').textContent = data.reason;
                    }
                })
                .catch(err => console.error(err));
        }

        function showReason() {
            const reasonContainer = document.getElementById('reason-container');
            const showButton = document.getElementById('show-reason-btn');

            reasonContainer.style.display = 'block';
            showButton.style.display = 'none';

            // animação de aparição
            reasonContainer.style.opacity = '0';
            reasonContainer.style.transform = 'translateY(20px)';
            setTimeout(() => {
                reasonContainer.style.transition = 'all 0.4s ease-out';
                reasonContainer.style.opacity = '1';
                reasonContainer.style.transform = 'translateY(0)';
            }, 50);
        }

        function nextQuestion() {
            const params = new URLSearchParams(window.location.search);
            window.location.href = "{{ route('quiz') }}?" + params.toString();
        }
    </script>
@endsection
