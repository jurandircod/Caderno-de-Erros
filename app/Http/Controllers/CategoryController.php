<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NotificationController;

class CategoryController extends Controller
{
    public function index()
    {
        $userId = Auth::check() ? Auth::id() : null;
        if (!$userId) {
            // Se o usuário não está autenticado, redireciona para a página de login
            return NotificationController::redirectWithNotification('login', 'Você precisa estar logado para acessar as categorias.', 'error');
        }
        $categories = Category::where('user_id', $userId)->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name|max:255'
        ]);
        $userId = Auth::check() ? Auth::id() : null;
        if (!$userId) {
            // Se o usuário não está autenticado, redireciona para a página de login
            return NotificationController::redirectWithNotification('login', 'Você precisa estar logado para criar categorias.', 'error');
        }
        Category::create([
            'name' => $request->name,
            'user_id' => $userId,
        ]);

        return redirect()->back()->with('success', 'Categoria criada com sucesso!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Categoria excluída com sucesso!');
    }
}
