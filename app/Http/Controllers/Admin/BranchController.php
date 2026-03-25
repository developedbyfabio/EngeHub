<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount('formLinks')->orderBy('name')->get();

        return view('admin.branches.index', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:branches,slug',
        ]);

        if (empty($validated['slug'])) {
            unset($validated['slug']);
        }

        Branch::create($validated);

        $redirect = $request->input('_redirect', route('admin.branches.index'));

        return redirect($redirect)
            ->with('success', 'Filial criada com sucesso.');
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:branches,slug,' . $branch->id,
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        $branch->update($validated);

        $redirect = $request->input('_redirect', route('admin.branches.index'));

        return redirect($redirect)
            ->with('success', 'Filial atualizada com sucesso.');
    }

    public function destroy(Request $request, Branch $branch)
    {
        $branch->delete();

        $redirect = $request->input('_redirect', route('admin.branches.index'));

        return redirect($redirect)
            ->with('success', 'Filial excluída com sucesso.');
    }
}
