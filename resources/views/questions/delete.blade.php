@extends('layouts.app')

@section('title', 'Excluir Questões - Caderno de Erros')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card question-card">
                <div class="card-body p-4">
                    <h4 class="mb-4"><i class="fas fa-trash-alt"></i> Excluir Questões</h4>
                    @if ($questions->isEmpty())
                        <div class="alert alert-info text-center">
                            <h5>Não há questões cadastradas ainda.</h5>
                            <a href="{{ route('questions.create') }}" class="btn btn-primary btn-custom mt-3">
                                <i class="fas fa-plus-circle"></i> Criar Nova Questão
                            </a>
                        </div>
                    @else
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Questão</th>
                                    <th>Categoria</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($questions as $question)
                                    <tr>
                                        <td>{{ $question->id }}</td>
                                        <td>{{ Str::limit($question->question_text, 80) }}</td>
                                        <td>{{ $question->category->name ?? 'Sem categoria' }}</td>

                                        <td class="text-center">
                                            <!-- Botão para abrir modal de confirmação -->
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $question->id }}">
                                                <i class="fas fa-trash"></i> Excluir
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal de Confirmação -->
                                    <div class="modal fade" id="deleteModal{{ $question->id }}" tabindex="-1"
                                        aria-labelledby="deleteModalLabel{{ $question->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $question->id }}">
                                                        Confirmar Exclusão
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Tem certeza que deseja excluir esta questão?
                                                    <p class="mt-2"><strong>{{ $question->question_text }}</strong></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancelar</button>
                                                    <form action="{{ route('questions.destroy', $question->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-trash-alt"></i> Excluir
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    <div class="text-center mt-4">
                        <a href="{{ route('quiz') }}" class="btn btn-secondary btn-custom">
                            <i class="fas fa-arrow-left"></i> Voltar ao Quiz
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
