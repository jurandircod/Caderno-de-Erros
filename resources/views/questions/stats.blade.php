@extends('layouts.app')

@section('title', 'Estatísticas - Caderno de Erros')

@section('content')
<div class="container my-4">
    <h2 class="mb-4 text-center"><i class="fas fa-chart-line"></i> Estatísticas do Caderno de Erros</h2>

    <!-- Filtro por categorias -->
    <form method="GET" class="mb-4">
        <label for="categories" class="form-label fw-bold">Filtrar por Categoria:</label>
        <select name="categories[]" id="categories" class="form-select" multiple>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" 
                    {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-filter"></i> Filtrar</button>
    </form>

    <div class="row g-4">

        <!-- Questões Mais Erradas -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="fas fa-times-circle"></i> Questões Mais Erradas</h4>
                </div>
                <div class="card-body p-3">
                    @if($mostWrong->isEmpty())
                        <p class="text-muted">Nenhuma questão errada registrada com esses filtros.</p>
                    @else
                        @foreach($mostWrong->groupBy('category.name') as $categoryName => $questions)
                            <h5 class="mt-3">{{ $categoryName ?? 'Sem Categoria' }}</h5>
                            <ul class="list-group list-group-flush mb-3">
                                @foreach($questions as $q)
                                    <li class="list-group-item d-flex flex-column">
                                        <strong>{{ $q->question_text }}</strong>
                                        <div class="mt-1">
                                            <span class="badge bg-danger me-2">Erros: {{ $q->wrong_count }}</span>
                                            <span class="badge bg-success">Acertos: {{ $q->correct_count }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Questões Mais Acertadas -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-check-circle"></i> Questões Mais Acertadas</h4>
                </div>
                <div class="card-body p-3">
                    @if($mostCorrect->isEmpty())
                        <p class="text-muted">Nenhuma questão acertada registrada com esses filtros.</p>
                    @else
                        @foreach($mostCorrect->groupBy('category.name') as $categoryName => $questions)
                            <h5 class="mt-3">{{ $categoryName ?? 'Sem Categoria' }}</h5>
                            <ul class="list-group list-group-flush mb-3">
                                @foreach($questions as $q)
                                    <li class="list-group-item d-flex flex-column">
                                        <strong>{{ $q->question_text }}</strong>
                                        <div class="mt-1">
                                            <span class="badge bg-success me-2">Acertos: {{ $q->correct_count }}</span>
                                            <span class="badge bg-danger">Erros: {{ $q->wrong_count }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
