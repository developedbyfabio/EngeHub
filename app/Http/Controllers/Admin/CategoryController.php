<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('cards')->orderBy('order', 'asc')->get();
        
        if (request()->ajax()) {
            return response()->json([
                'categories' => $categories
            ]);
        }
        
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (request()->ajax()) {
            return response()->json([
                'html' => view('admin.categories.create')->render()
            ]);
        }
        
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|max:7',
            'order' => 'required|integer|min:0'
        ]);

        $category = Category::create($request->all());

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Categoria criada com sucesso!',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'cards_count' => 0
                ]
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        
        if (request()->ajax()) {
            return response()->json([
                'html' => view('admin.categories.edit', compact('category'))->render()
            ]);
        }
        
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|max:7',
            'order' => 'required|integer|min:0'
        ]);

        $category->update($request->all());

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Categoria atualizada com sucesso!',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'cards_count' => $category->cards()->count()
                ]
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Categoria excluída com sucesso!',
                'category_id' => $id
            ]);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoria excluída com sucesso!');
    }

    /**
     * Retorna todas as categorias para uso em dropdowns
     */
    public function getAll()
    {
        $categories = Category::orderBy('name', 'asc')->get(['id', 'name', 'color']);
        
        return response()->json($categories);
    }
}
