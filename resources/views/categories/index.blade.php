@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Gerenciar Categorias</h2>

    {{-- Formulário para adicionar nova categoria --}}
    <form action="{{ route('categories.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="form-group">
            <label for="name">Nome da Categoria</label>
            <input type="text" name="name" id="name" class="form-control" required>
            @error('name')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary mt-2">Adicionar</button>
    </form>

    {{-- Lista de categorias --}}
    <h4>Categorias Cadastradas</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Criada em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" 
                              onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">Nenhuma categoria cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
