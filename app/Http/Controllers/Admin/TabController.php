<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tab;
use Illuminate\Http\Request;

class TabController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.cards.index');
    }

    public function create()
    {
        if (request()->ajax()) {
            return response()->json([
                'html' => view('admin.tabs.create')->render()
            ]);
        }
        
        return view('admin.tabs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|max:7',
            'order' => 'required|integer|min:0'
        ]);

        Tab::create($request->all());

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Aba criada com sucesso!',
                'redirect' => route('admin.cards.index')
            ]);
        }

        return redirect()->route('admin.cards.index')
            ->with('success', 'Aba criada com sucesso!');
    }

    public function edit(Tab $tab)
    {
        if (request()->ajax()) {
            return response()->json([
                'html' => view('admin.tabs.edit', compact('tab'))->render()
            ]);
        }
        
        return view('admin.tabs.edit', compact('tab'));
    }

    public function update(Request $request, Tab $tab)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|max:7',
            'order' => 'required|integer|min:0'
        ]);

        $tab->update($request->all());

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Aba atualizada com sucesso!',
                'redirect' => route('admin.cards.index')
            ]);
        }

        return redirect()->route('admin.cards.index')
            ->with('success', 'Aba atualizada com sucesso!');
    }

    public function destroy(Tab $tab)
    {
        $tab->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Aba excluída com sucesso!',
                'redirect' => route('admin.cards.index')
            ]);
        }

        return redirect()->route('admin.cards.index')
            ->with('success', 'Aba excluída com sucesso!');
    }
} 