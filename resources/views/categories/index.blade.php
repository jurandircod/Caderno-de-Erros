@extends('layouts.app')

@section('content')
<div class="animate-fade-in-up">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-yellow-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-tags text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-bold gradient-text">Gerenciar Categorias</h2>
                <p class="text-gray-600 font-medium">Organize suas questões por categorias</p>
            </div>
        </div>
    </div>

    <!-- Add Category Form -->
    <div class="bg-gradient-to-br from-orange-50 to-yellow-50 rounded-3xl p-8 border border-orange-100 mb-8 shadow-xl animate-slide-in" style="animation-delay: 0.2s">
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-yellow-600 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-plus-circle text-white"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Nova Categoria</h3>
        </div>

        <form action="{{ route('categories.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="relative">
                <label for="name" class="block text-sm font-bold text-gray-700 mb-3">Nome da Categoria</label>
                <div class="relative">
                    <input type="text" name="name" id="name" required
                        class="w-full px-6 py-4 bg-white border-2 border-orange-200 rounded-2xl focus:border-orange-500 focus:ring-4 focus:ring-orange-100 transition-all duration-300 text-gray-800 placeholder-gray-400 font-medium text-lg shadow-sm hover:shadow-md"
                        placeholder="Digite o nome da categoria...">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-6">
                        <i class="fas fa-tag text-orange-400 text-lg"></i>
                    </div>
                </div>
                @error('name')
                <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-xl">
                    <div class="text-red-600 text-sm font-semibold flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ $message }}
                    </div>
                </div>
                @enderror
            </div>
            <button type="submit"
                class="px-8 py-4 bg-gradient-to-r from-orange-500 to-yellow-600 text-white rounded-2xl font-bold text-lg hover:shadow-xl hover:scale-105 transition-all duration-300 flex items-center space-x-3 shadow-lg">
                <i class="fas fa-plus"></i>
                <span>Adicionar Categoria</span>
            </button>
        </form>
    </div>

    <!-- Categories List -->
    <div class="bg-white/80 backdrop-blur-lg rounded-3xl border border-white/20 overflow-hidden shadow-2xl animate-fade-in-up" style="animation-delay: 0.4s">
        <div class="px-8 py-6 bg-gradient-to-r from-blue-500 to-indigo-600">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-list mr-3"></i>
                Categorias Cadastradas
            </h3>
        </div>

        <div class="p-8">
            @forelse ($categories as $category)
                <div class="bg-white rounded-2xl shadow-lg mb-6">
                    <div class="p-6 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-folder text-white text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-bold">{{ $category->name }}</h4>
                                <p class="text-sm text-gray-500">Criada em {{ $category->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button class="open-modal inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border"
                                data-modal-target="modal-edit-cat-{{ $category->id }}">
                                <i class="fas fa-edit"></i> Editar
                            </button>

                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                onsubmit="return confirm('Excluir categoria?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-3 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-xl">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal de edição da categoria (por item) -->
                <div id="modal-edit-cat-{{ $category->id }}" class="modal fixed inset-0 z-50 hidden items-center justify-center px-4">
                    <div class="modal-backdrop absolute inset-0 bg-black/50"></div>
                    <div class="relative w-full max-w-lg mx-auto">
                        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl">
                            <div class="p-6 bg-gradient-to-r from-orange-500 to-yellow-600 text-white flex items-center justify-between">
                                <h3 class="text-xl font-bold">Editar Categoria</h3>
                                <button type="button" class="close-modal text-white text-xl font-bold px-3 py-1">&times;</button>
                            </div>
                            <div class="p-6">
                                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <label class="block text-sm font-semibold mb-2">Nome</label>
                                    <input name="name" type="text" value="{{ old('name', $category->name) }}" required
                                        class="w-full px-4 py-3 border rounded-lg mb-4">
                                    <div class="flex justify-end gap-3">
                                        <button type="button" class="close-modal px-4 py-2 border rounded-lg">Cancelar</button>
                                        <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-lg">Salvar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <i class="fas fa-inbox text-gray-400 text-3xl"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-600 mb-3">Nenhuma categoria cadastrada</h4>
                    <p class="text-gray-500 text-lg mb-6">Adicione sua primeira categoria usando o formulário acima.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex justify-center mt-8 animate-fade-in-up" style="animation-delay: 0.6s">
        <a href="{{ route('quiz') }}"
            class="px-8 py-4 bg-white text-gray-700 rounded-2xl font-bold text-lg border-2 border-gray-200 hover:bg-gray-50 hover:shadow-lg transition-all duration-300 flex items-center space-x-3 hover:scale-105">
            <i class="fas fa-arrow-left"></i>
            <span>Voltar ao Quiz</span>
        </a>
    </div>
</div>
@endsection
