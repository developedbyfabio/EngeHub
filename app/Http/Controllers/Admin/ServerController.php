<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\DataCenter;
use App\Models\ServerGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servers = Server::with(['dataCenter', 'serverGroup'])->orderBy('name')->get();
        $serverGroups = ServerGroup::active()->ordered()->get();
        
        return view('admin.servers.index', compact('servers', 'serverGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $datacenters = DataCenter::orderBy('name')->get();
        $serverGroups = ServerGroup::active()->ordered()->get();
        
        if (request()->ajax()) {
            return response()->json([
                'html' => view('admin.servers.create', compact('datacenters', 'serverGroups'))->render()
            ]);
        }
        
        return view('admin.servers.create', compact('datacenters', 'serverGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('ServerController::store - Iniciando criação de servidor', [
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
            'is_ajax' => request()->ajax()
        ]);

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'ip_address' => 'required|ip',
                'data_center_id' => 'nullable|exists:data_centers,id',
                'description' => 'nullable|string',
                'webmin_url' => 'nullable|url',
                'nginx_url' => 'nullable|url',
                'operating_system' => 'nullable|in:Linux,Windows,Outros',
                'server_group_id' => 'nullable|exists:server_groups,id',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
                'monitor_status' => 'boolean'
            ]);

            $data = $request->all();
            // Processar monitor_status corretamente
            $data['monitor_status'] = $request->input('monitor_status', '0') === '1' || $request->boolean('monitor_status');

            \Log::info('ServerController::store - Dados processados', [
                'data' => $data
            ]);

            // Upload da logo
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = time() . '_' . $logo->getClientOriginalName();
                
                \Log::info('ServerController::store - Upload de logo', [
                    'original_name' => $logo->getClientOriginalName(),
                    'logo_name' => $logoName,
                    'size' => $logo->getSize(),
                    'mime_type' => $logo->getMimeType()
                ]);
                
                $path = $logo->storeAs('public/server_logos', $logoName);
                $data['logo_path'] = 'server_logos/' . $logoName;
                
                \Log::info('ServerController::store - Logo salva', [
                    'path' => $path,
                    'logo_path' => $data['logo_path']
                ]);
            }

            $server = Server::create($data);

            \Log::info('ServerController::store - Servidor criado com sucesso', [
                'server_id' => $server->id,
                'server_name' => $server->name
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Servidor criado com sucesso!',
                    'redirect' => route('admin.servers.index')
                ]);
            }

            return redirect()->route('admin.servers.index')->with('success', 'Servidor criado com sucesso!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('ServerController::store - Erro de validação', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            \Log::error('ServerController::store - Erro geral', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro interno do servidor: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erro ao criar servidor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Server $server)
    {
        $server->load('dataCenter');
        return view('admin.servers.show', compact('server'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Server $server)
    {
        $datacenters = DataCenter::orderBy('name')->get();
        $serverGroups = ServerGroup::active()->ordered()->get();
        
        if (request()->ajax()) {
            return response()->json([
                'html' => view('admin.servers.edit', compact('server', 'datacenters', 'serverGroups'))->render()
            ]);
        }
        
        return view('admin.servers.edit', compact('server', 'datacenters', 'serverGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Server $server)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ip',
            'data_center_id' => 'nullable|exists:data_centers,id',
            'description' => 'nullable|string',
            'webmin_url' => 'nullable|url',
            'nginx_url' => 'nullable|url',
            'operating_system' => 'nullable|in:Linux,Windows,Outros',
            'server_group_id' => 'nullable|exists:server_groups,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'monitor_status' => 'boolean'
        ]);

        $data = $request->all();
        // Processar monitor_status corretamente
        $data['monitor_status'] = $request->input('monitor_status', '0') === '1' || $request->boolean('monitor_status');

        // Upload da nova logo
        if ($request->hasFile('logo')) {
            // Deletar logo antiga se existir
            if ($server->logo_path) {
                Storage::disk('public')->delete($server->logo_path);
            }
            
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            
            \Log::info('ServerController::update - Upload de nova logo', [
                'original_name' => $logo->getClientOriginalName(),
                'logo_name' => $logoName,
                'size' => $logo->getSize(),
                'mime_type' => $logo->getMimeType()
            ]);
            
            $path = $logo->storeAs('public/server_logos', $logoName);
            $data['logo_path'] = 'server_logos/' . $logoName;
            
            \Log::info('ServerController::update - Nova logo salva', [
                'path' => $path,
                'logo_path' => $data['logo_path']
            ]);
        }

        $server->update($data);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Servidor atualizado com sucesso!',
                'redirect' => route('admin.servers.index')
            ]);
        }

        return redirect()->route('admin.servers.index')->with('success', 'Servidor atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Server $server)
    {
        try {
            // Deletar logo se existir
            if ($server->logo_path) {
                Storage::disk('public')->delete($server->logo_path);
            }

            $serverName = $server->name;
            $server->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Servidor excluído com sucesso!'
                ]);
            }

            return redirect()->route('admin.servers.index')->with('success', 'Servidor excluído com sucesso!');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao excluir servidor!'
                ], 500);
            }

            return redirect()->route('admin.servers.index')->with('error', 'Erro ao excluir servidor!');
        }
    }

    /**
     * Verifica o status de um servidor específico
     */
    public function checkStatus(Server $server)
    {
        $status = $server->checkStatus();
        
        return response()->json([
            'success' => true,
            'message' => 'Status verificado com sucesso!',
            'status' => $server->status,
            'status_text' => $server->status_text,
            'status_class' => $server->status_class,
            'response_time' => $server->response_time,
            'last_check' => $server->last_status_check ? $server->last_status_check->format('d/m/Y H:i:s') : null
        ]);
    }
}
