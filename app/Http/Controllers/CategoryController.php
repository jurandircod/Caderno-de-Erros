<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // Lista categorias (já usada na sua view)
    public function index()
    {
        $userId = Auth::id();
        $categories = Category::where('user_id', $userId)->orderBy('name')->get();
        return view('categories.index', compact('categories'));
    }

    // Cria nova
    public function store(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,NULL,id,user_id,'.$userId,
        ]);

        Category::create([
            'name' => $request->name,
            'user_id' => $userId,
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoria criada com sucesso!');
    }

    // Atualiza via PATCH
    public function update(Request $request, Category $category)
    {
        $userId = Auth::id();
        // opcional: checar propriedade
        if ($category->user_id && $category->user_id !== $userId) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id.',id,user_id,'.$userId,
        ]);

        $category->update([
            'name' => $request->name
        ]);

        return redirect()->route('categories.index')->with('success', 'Categoria atualizada!');
    }

    public function destroy(Category $category)
    {
        $userId = Auth::id();
        if ($category->user_id && $category->user_id !== $userId) {
            abort(403);
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoria excluída.');
    }
}
