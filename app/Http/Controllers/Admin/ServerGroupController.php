<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServerGroup;
use Illuminate\Http\Request;

class ServerGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $serverGroups = ServerGroup::withCount('servers')->ordered()->get();
        
        if (request()->ajax()) {
            return response()->json([
                'serverGroups' => $serverGroups
            ]);
        }
        
        return view('admin.server-groups.index', compact('serverGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.server-groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:server_groups',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        ServerGroup::create($data);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Grupo de servidores criado com sucesso!',
                'redirect' => route('admin.server-groups.index')
            ]);
        }

        return redirect()->route('admin.server-groups.index')->with('success', 'Grupo de servidores criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ServerGroup $serverGroup)
    {
        $serverGroup->load('servers');
        return view('admin.server-groups.show', compact('serverGroup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServerGroup $serverGroup)
    {
        if (request()->ajax()) {
            return response()->json([
                'group' => $serverGroup
            ]);
        }
        
        return view('admin.server-groups.edit', compact('serverGroup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServerGroup $serverGroup)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:server_groups,name,' . $serverGroup->id,
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $data = $request->all();
        // Manter o status ativo atual (não alterar)
        $data['is_active'] = $serverGroup->is_active;

        $serverGroup->update($data);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Grupo de servidores atualizado com sucesso!',
                'redirect' => route('admin.server-groups.index')
            ]);
        }

        return redirect()->route('admin.server-groups.index')->with('success', 'Grupo de servidores atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServerGroup $serverGroup)
    {
        // Verificar se há servidores associados
        if ($serverGroup->servers()->count() > 0) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não é possível excluir o grupo pois há servidores associados a ele.'
                ], 422);
            }
            return back()->with('error', 'Não é possível excluir o grupo pois há servidores associados a ele.');
        }

        $serverGroup->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Grupo de servidores excluído com sucesso!'
            ]);
        }

        return redirect()->route('admin.server-groups.index')->with('success', 'Grupo de servidores excluído com sucesso!');
    }
}