@extends('layouts.app')

@section('title', 'Excluir Questões - Caderno de Erros')

@section('content')
<div class="animate-fade-in-up max-w-6xl mx-auto py-8">
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-red-500 to-pink-600 rounded-3xl mb-4 shadow-2xl">
            <i class="fas fa-trash-alt text-white text-3xl"></i>
        </div>
        <h1 class="text-4xl font-bold text-gray-800 mb-2">Excluir Questões</h1>
        <p class="text-gray-600">Remova questões que não são mais necessárias — ação irreversível.</p>
    </div>

    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 overflow-hidden p-6">
        @if ($questions->isEmpty())
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <i class="fas fa-question text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-2">Nenhuma questão cadastrada</h3>
                <p class="text-gray-500 mb-6">Adicione questões para começar a construir seu banco de estudos.</p>
                <a href="{{ route('questions.create') }}"
                   class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl font-semibold shadow-lg hover:scale-[1.02] transition">
                    <i class="fas fa-plus-circle"></i> Criar Nova Questão
                </a>
            </div>
        @else
            @php
                $grouped = $questions->groupBy(fn($q) => $q->category->name ?? 'Sem categoria');
            @endphp

            <div class="space-y-4">
                @foreach ($grouped as $categoryName => $catQuestions)
                    <details class="border rounded-2xl overflow-hidden shadow group">
                        <summary class="cursor-pointer list-none px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 font-semibold flex items-center justify-between">
                            <span>{{ $categoryName }} ({{ $catQuestions->count() }} questões)</span>
                            <i class="fas fa-chevron-down transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>

                        <div class="divide-y">
                            @foreach ($catQuestions as $question)
                                <div class="p-4">
                                    <div class="flex justify-between items-start gap-4">
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-800">{{ Str::limit($question->question_text, 180) }}</p>
                                            <p class="text-sm text-gray-500 mt-1">
                                                ID #{{ $question->id }} •
                                                Acertos: <strong>{{ $question->correct_count }}</strong> •
                                                Erros: <strong>{{ $question->wrong_count }}</strong>
                                            </p>
                                        </div>

                                        <div class="flex items-center gap-2 shrink-0">
                                            <!-- Abre o colapse de edição abaixo -->
                                            <label for="edit-{{ $question->id }}"
                                                   class="inline-flex items-center gap-2 px-3 py-2 bg-white border rounded-xl shadow hover:shadow-md transition text-indigo-600 cursor-pointer">
                                                <i class="fas fa-edit"></i> Editar
                                            </label>

                                            <!-- Excluir -->
                                            <form action="{{ route('questions.destroy', $question->id) }}" method="POST"
                                                  onsubmit="return confirm('Tem certeza que deseja excluir esta questão?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-2 px-3 py-2 rounded-xl font-semibold shadow transform hover:scale-[1.02] transition bg-gradient-to-r from-red-500 to-pink-600 text-white">
                                                    <i class="fas fa-trash"></i> Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Colapse de edição da questão -->
                                    <input id="edit-{{ $question->id }}" type="checkbox" class="peer hidden">
                                    <div class="mt-4 rounded-2xl border bg-gray-50/60 p-4 peer-checked:block hidden">
                                        <form action="{{ route('questions.update', $question->id) }}" method="POST" class="space-y-4">
                                            @csrf
                                            @method('PATCH')

                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-2">Texto da Questão</label>
                                                <textarea name="question_text" rows="3" class="w-full px-4 py-3 border rounded-lg">{{ old('question_text', $question->question_text) }}</textarea>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                @foreach (['a','b','c','d','e'] as $opt)
                                                    <div>
                                                        <label class="block text-sm font-bold text-gray-700 mb-2">Opção {{ strtoupper($opt) }}</label>
                                                        <input type="text" name="option_{{ $opt }}"
                                                               value="{{ old('option_'.$opt, $question->options[$opt] ?? '') }}"
                                                               class="w-full px-4 py-3 border rounded-lg">
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-sm font-bold text-gray-700 mb-2">Categoria</label>
                                                    <select name="category_id" class="w-full px-4 py-3 border rounded-lg">
                                                        <option value="">-- Sem categoria --</option>
                                                        @foreach ($categories as $cat)
                                                            <option value="{{ $cat->id }}" {{ $question->category_id == $cat->id ? 'selected' : '' }}>
                                                                {{ $cat->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-bold text-gray-700 mb-2">Resposta Correta</label>
                                                    <select name="correct_answer" class="w-full px-4 py-3 border rounded-lg">
                                                        @foreach (['a','b','c','d','e'] as $opt)
                                                            <option value="{{ $opt }}" {{ $question->correct_answer === $opt ? 'selected' : '' }}>
                                                                Opção {{ strtoupper($opt) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 mb-2">Explicação</label>
                                                <textarea name="reason" rows="3" class="w-full px-4 py-3 border rounded-lg">{{ old('reason', $question->reason) }}</textarea>
                                            </div>

                                            <div class="flex justify-end gap-2">
                                                <label for="edit-{{ $question->id }}" class="px-4 py-2 border rounded-lg cursor-pointer">Cancelar</label>
                                                <button type="submit" class="px-5 py-3 rounded-lg bg-indigo-600 text-white">Salvar Alterações</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </details>
                @endforeach
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('quiz') }}" class="inline-flex items-center gap-3 px-6 py-3 bg-white border border-gray-200 rounded-2xl font-semibold hover:shadow-md transition">
                    <i class="fas fa-arrow-left text-gray-700"></i> Voltar ao Quiz
                </a>
            </div>
        @endif
    </div>
</div>

<style>
/* gira o chevron quando aberto */
details[open] summary i { transform: rotate(180deg); }
</style>
@endsection
