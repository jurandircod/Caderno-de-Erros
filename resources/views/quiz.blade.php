@extends('layouts.app')

@section('title', 'Quiz - Caderno de Erros')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">

        {{-- Filtro de Categorias --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5>Filtrar por Categorias</h5>
                <form method="GET" action="{{ route('quiz') }}">
                    <div class="row">
                        @foreach ($categories as $category)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="categories[]" 
                                           value="{{ $category->id }}"
                                           id="cat{{ $category->id }}"
                                           {{ in_array($category->id, $selectedCategories ?? []) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cat{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Aplicar Filtro</button>
                </form>
            </div>
        </div>

        <div class="question-card card">
            <div class="card-body p-4">
                @if (isset($message))
                    <div class="alert alert-info text-center">
                        <h4>{{ $message }}</h4>
                        <a href="{{ route('questions.create') }}" class="btn btn-primary btn-custom mt-3">
                            <i class="fas fa-plus"></i> Criar Primeira Questão
                        </a>
                    </div>
                @else
                    <p><strong>Categoria:</strong> {{ $question->category->name ?? 'Não definida' }}</p>

                    <div id="question-container">
                        <h4 class="mb-4" id="question-text">{{ $question->question_text }}</h4>
                        <div id="options-container">
                            @foreach ($question->options as $key => $option)
                                <button class="btn option-btn btn-outline-primary mb-2" data-answer="{{ $key }}">
                                    <strong>{{ strtoupper($key) }})</strong> {{ $option }}
                                </button>
                            @endforeach
                        </div>

                        <div id="reason-container" class="reason-box mt-3" style="display: none;">
                            <h6><i class="fas fa-lightbulb"></i> Explicação:</h6>
                            <p id="reason-text">{{ $question->reason }}</p>
                        </div>

                        <div class="text-center mt-4">
                            <button id="show-reason-btn" class="btn btn-info me-2" onclick="showReason()" style="display:none;">
                                <i class="fas fa-eye"></i> Mostrar Explicação
                            </button>
                            <button id="next-question-btn" class="btn btn-success" onclick="nextQuestion()">
                                <i class="fas fa-forward"></i> Próxima Questão
                            </button>
                        </div>
                    </div>

                    <input type="hidden" id="current-question-id" value="{{ $question->id }}">
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let answered = false;

    // Configura o clique nos botões de opção
    document.querySelectorAll('.option-btn').forEach(btn => {
        btn.addEventListener('click', () => checkAnswer(btn));
    });

    function checkAnswer(button) {
        if (answered) return;
        answered = true;

        const selectedAnswer = button.getAttribute('data-answer');
        const questionId = document.getElementById('current-question-id').value;

        // Desabilitar todos os botões
        document.querySelectorAll('.option-btn').forEach(b => b.disabled = true);

        fetch('{{ route("quiz.check") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ question_id: questionId, answer: selectedAnswer })
        })
        .then(res => res.json())
        .then(data => {
            document.querySelectorAll('.option-btn').forEach(b => {
                const opt = b.getAttribute('data-answer');
                if(opt === data.correct_answer) b.classList.replace('btn-outline-primary','btn-success');
                if(opt === selectedAnswer && !data.correct) b.classList.replace('btn-outline-primary','btn-danger');
            });

            document.getElementById('show-reason-btn').style.display = 'inline-block';
        })
        .catch(err => console.error(err));
    }

    function showReason() {
        document.getElementById('reason-container').style.display = 'block';
        document.getElementById('show-reason-btn').style.display = 'none';
    }

    function nextQuestion() {
        const params = new URLSearchParams(window.location.search);
        window.location.href = "{{ route('quiz') }}?" + params.toString();
    }
</script>
@endsection
