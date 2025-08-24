@extends('layouts.app')

@section('title', 'Nova Questão - Caderno de Erros')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card question-card">
                <div class="card-body p-4">
                    <h4 class="mb-4"><i class="fas fa-plus-circle"></i> Criar Nova Questão</h4>

                    <form id="question-form" action="{{ route('questions.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold">Texto da Questão</label>
                            <textarea id="question_text" class="form-control @error('question_text') is-invalid @enderror" name="question_text"
                                rows="6" placeholder="Digite o enunciado da questão...">{{ old('question_text') }}</textarea>
                            @error('question_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">💡 Dica: você pode colar a questão inteira com as opções A), B), C),
                                D) que o sistema separa automaticamente.</small>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Opção A</label>
                                <input type="text" id="option_a"
                                    class="form-control @error('option_a') is-invalid @enderror" name="option_a"
                                    value="{{ old('option_a') }}" placeholder="Digite a opção A">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Opção B</label>
                                <input type="text" id="option_b"
                                    class="form-control @error('option_b') is-invalid @enderror" name="option_b"
                                    value="{{ old('option_b') }}" placeholder="Digite a opção B">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Opção C</label>
                                <input type="text" id="option_c"
                                    class="form-control @error('option_c') is-invalid @enderror" name="option_c"
                                    value="{{ old('option_c') }}" placeholder="Digite a opção C">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Opção D</label>
                                <input type="text" id="option_d"
                                    class="form-control @error('option_d') is-invalid @enderror" name="option_d"
                                    value="{{ old('option_d') }}" placeholder="Digite a opção D">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Categoria</label> <select name="category_id"
                                    id="category_id" class="form-control" required>
                                    <option value="">-- Selecione uma Categoria --</option>
                                    @foreach (\App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Resposta Correta</label>
                                <select class="form-select @error('correct_answer') is-invalid @enderror"
                                    name="correct_answer">
                                    <option value="">Selecione a resposta correta</option>
                                    <option value="a" {{ old('correct_answer') === 'a' ? 'selected' : '' }}>Opção A
                                    </option>
                                    <option value="b" {{ old('correct_answer') === 'b' ? 'selected' : '' }}>Opção B
                                    </option>
                                    <option value="c" {{ old('correct_answer') === 'c' ? 'selected' : '' }}>Opção C
                                    </option>
                                    <option value="d" {{ old('correct_answer') === 'd' ? 'selected' : '' }}>Opção D
                                    </option>
                                </select>
                            </div>
                        </div>


                        <div class="mb-4">
                            <label class="form-label fw-bold">Explicação da Resposta</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" name="reason" rows="3"
                                placeholder="Explique por que esta é a resposta correta...">{{ old('reason') }}</textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-custom">
                                <i class="fas fa-save"></i> Salvar Questão
                            </button>
                            <a href="{{ route('quiz') }}" class="btn btn-secondary btn-custom">
                                <i class="fas fa-arrow-left"></i> Voltar ao Quiz
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById("question_text").addEventListener("paste", function(e) {
            // aguarda um pouco porque o valor do textarea só fica disponível após o paste
            setTimeout(() => {
                let text = e.target.value;

                // Regex para separar enunciado e opções
                let regex = /(.*?)\s*A\)(.*?)\s*B\)(.*?)\s*C\)(.*?)\s*D\)(.*)/s;
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
                }
            }, 50);
        });
    </script>
@endsection
