<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemUser;
use App\Models\Card;
use App\Models\Tab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SectorController extends Controller
{
    /**
     * Exibe a lista de setores
     * Mostra apenas SystemUsers criados como setores (sem user_id vinculado)
     */
    public function index()
    {
        $sectors = SystemUser::with(['cards', 'secretUrlAccessLogs' => function($query) {
            $query->latest('accessed_at')->limit(1);
        }])
        ->whereNull('user_id') // Apenas setores puros, não usuários de login
        ->orderBy('name')
        ->get();
        
        return view('admin.sectors.index', compact('sectors'));
    }

    /**
     * Exibe formulário de criação
     */
    public function create()
    {
        $tabs = Tab::with('cards')->orderBy('order')->get();
        
        if (request()->ajax()) {
            return response()->json([
                'html' => view('admin.sectors.create', compact('tabs'))->render()
            ]);
        }
        
        return view('admin.sectors.create', compact('tabs'));
    }

    /**
     * Salva novo setor
     */
    public function store(Request $request)
    {
        Log::info('SectorController::store - Dados recebidos', [
            'all' => $request->all(),
            'name' => $request->name,
            'cards' => $request->cards,
            'is_ajax' => $request->ajax()
        ]);
        
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'cards' => 'nullable|array',
                'cards.*' => 'exists:cards,id',
                'generate_url' => 'nullable'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('SectorController::store - Erro de validação', [
                'errors' => $e->errors()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação: ' . implode(', ', array_map(fn($msgs) => implode(', ', $msgs), $e->errors()))
                ], 422);
            }
            throw $e;
        }
        
        try {
            DB::beginTransaction();
            
            // Criar SystemUser (setor) com username único
            $baseUsername = 'setor_' . \Str::slug($request->name);
            $username = $baseUsername;
            $counter = 1;
            
            // Garantir username único
            while (SystemUser::where('username', $username)->exists()) {
                $username = $baseUsername . '_' . $counter;
                $counter++;
            }
            
            $sector = SystemUser::create([
                'name' => $request->name,
                'username' => $username,
                'password' => 'N/A',
                'notes' => $request->notes,
                'is_active' => true,
                'user_id' => null
            ]);
            
            // Associar cards selecionados
            if ($request->has('cards') && is_array($request->cards)) {
                $sector->cards()->sync($request->cards);
            }
            
            // Gerar URL secreta se solicitado
            if ($request->generate_url) {
                $sector->generateSecretUrl();
            }
            
            DB::commit();
            
            Log::info('Setor criado', [
                'sector_id' => $sector->id,
                'name' => $sector->name,
                'cards_count' => count($request->cards ?? [])
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Setor criado com sucesso!',
                    'sector' => $sector
                ]);
            }
            
            return redirect()->route('admin.sectors.index')
                           ->with('success', 'Setor criado com sucesso!');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao criar setor', ['error' => $e->getMessage()]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar setor: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Erro ao criar setor: ' . $e->getMessage());
        }
    }

    /**
     * Exibe formulário de edição
     */
    public function edit(SystemUser $sector)
    {
        $tabs = Tab::with('cards')->orderBy('order')->get();
        $selectedCards = $sector->cards->pluck('id')->toArray();
        
        if (request()->ajax()) {
            return response()->json([
                'html' => view('admin.sectors.edit', compact('sector', 'tabs', 'selectedCards'))->render()
            ]);
        }
        
        return view('admin.sectors.edit', compact('sector', 'tabs', 'selectedCards'));
    }

    /**
     * Atualiza setor
     */
    public function update(Request $request, SystemUser $sector)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cards' => 'nullable|array',
            'cards.*' => 'exists:cards,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            $sector->update([
                'name' => $request->name,
                'notes' => $request->notes,
                'is_active' => $request->has('is_active')
            ]);
            
            // Atualizar cards associados
            $sector->cards()->sync($request->cards ?? []);
            
            DB::commit();
            
            Log::info('Setor atualizado', [
                'sector_id' => $sector->id,
                'name' => $sector->name,
                'cards_count' => count($request->cards ?? [])
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Setor atualizado com sucesso!'
                ]);
            }
            
            return redirect()->route('admin.sectors.index')
                           ->with('success', 'Setor atualizado com sucesso!');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao atualizar setor', ['error' => $e->getMessage()]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar setor: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Erro ao atualizar setor: ' . $e->getMessage());
        }
    }

    /**
     * Exclui setor
     */
    public function destroy(SystemUser $sector)
    {
        try {
            $name = $sector->name;
            $sector->cards()->detach();
            $sector->delete();
            
            Log::info('Setor excluído', ['name' => $name]);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Setor excluído com sucesso!'
                ]);
            }
            
            return redirect()->route('admin.sectors.index')
                           ->with('success', 'Setor excluído com sucesso!');
                           
        } catch (\Exception $e) {
            Log::error('Erro ao excluir setor', ['error' => $e->getMessage()]);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao excluir setor: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Erro ao excluir setor: ' . $e->getMessage());
        }
    }

    /**
     * Gerenciar cards do setor
     */
    public function cards(SystemUser $sector)
    {
        $tabs = Tab::with('cards')->orderBy('order')->get();
        $selectedCards = $sector->cards->pluck('id')->toArray();
        
        if (request()->ajax()) {
            return response()->json([
                'html' => view('admin.sectors.cards', compact('sector', 'tabs', 'selectedCards'))->render()
            ]);
        }
        
        return view('admin.sectors.cards', compact('sector', 'tabs', 'selectedCards'));
    }

    /**
     * Atualizar cards do setor
     */
    public function updateCards(Request $request, SystemUser $sector)
    {
        $request->validate([
            'cards' => 'nullable|array',
            'cards.*' => 'exists:cards,id'
        ]);
        
        try {
            $sector->cards()->sync($request->cards ?? []);
            
            Log::info('Cards do setor atualizados', [
                'sector_id' => $sector->id,
                'cards_count' => count($request->cards ?? [])
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Links do setor atualizados com sucesso!',
                    'cards_count' => count($request->cards ?? [])
                ]);
            }
            
            return redirect()->route('admin.sectors.index')
                           ->with('success', 'Links do setor atualizados com sucesso!');
                           
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar cards do setor', ['error' => $e->getMessage()]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar links: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Erro ao atualizar links: ' . $e->getMessage());
        }
    }

    /**
     * Gerenciar URL secreta do setor
     */
    public function secretUrl(SystemUser $sector)
    {
        // Gerar URL se não existir
        if (!$sector->secret_url) {
            $sector->generateSecretUrl();
            $sector->refresh();
        }
        
        $recentLogs = $sector->secretUrlAccessLogs()
            ->orderBy('accessed_at', 'desc')
            ->limit(50)
            ->get();
        
        if (request()->ajax()) {
            return response()->json([
                'html' => view('admin.sectors.secret-url', compact('sector', 'recentLogs'))->render()
            ]);
        }
        
        return view('admin.sectors.secret-url', compact('sector', 'recentLogs'));
    }

    /**
     * Regenerar URL secreta (aleatória)
     */
    public function regenerateSecretUrl(SystemUser $sector)
    {
        try {
            $oldUrl = $sector->secret_url;
            $newUrl = $sector->regenerateSecretUrl();
            
            Log::info('URL secreta do setor regenerada', [
                'sector_id' => $sector->id,
                'old_url' => $oldUrl,
                'new_url' => $newUrl
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'URL secreta regenerada com sucesso!',
                'secret_url' => $sector->full_secret_url
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao regenerar URL secreta', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao regenerar URL: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualizar URL secreta personalizada
     */
    public function updateSecretUrl(Request $request, SystemUser $sector)
    {
        $request->validate([
            'secret_url' => 'required|string|min:3|max:50|regex:/^[a-zA-Z0-9_-]+$/'
        ], [
            'secret_url.required' => 'A URL é obrigatória.',
            'secret_url.min' => 'A URL deve ter no mínimo 3 caracteres.',
            'secret_url.max' => 'A URL deve ter no máximo 50 caracteres.',
            'secret_url.regex' => 'A URL deve conter apenas letras, números, hífens e underscores.'
        ]);
        
        try {
            $slug = \Str::slug($request->secret_url);
            
            // Verificar se já existe outro setor com essa URL
            $exists = SystemUser::where('secret_url', $slug)
                               ->where('id', '!=', $sector->id)
                               ->exists();
            
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta URL já está em uso por outro setor.'
                ], 422);
            }
            
            $oldUrl = $sector->secret_url;
            $sector->setCustomSecretUrl($slug);
            
            Log::info('URL secreta do setor atualizada', [
                'sector_id' => $sector->id,
                'old_url' => $oldUrl,
                'new_url' => $slug
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'URL atualizada com sucesso!',
                'secret_url' => $sector->full_secret_url,
                'slug' => $slug
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar URL secreta', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar URL: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Habilitar/Desabilitar URL secreta
     */
    public function toggleSecretUrl(SystemUser $sector)
    {
        try {
            if ($sector->secret_url_enabled) {
                $sector->disableSecretUrl();
                $message = 'URL secreta desabilitada!';
            } else {
                $sector->enableSecretUrl();
                $message = 'URL secreta habilitada!';
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'enabled' => $sector->secret_url_enabled
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Definir expiração da URL secreta
     */
    public function setSecretUrlExpiration(Request $request, SystemUser $sector)
    {
        $request->validate([
            'expires_at' => 'nullable|date|after:now'
        ]);
        
        try {
            $expiresAt = $request->expires_at ? now()->parse($request->expires_at) : null;
            
            $sector->update(['secret_url_expires_at' => $expiresAt]);
            
            return response()->json([
                'success' => true,
                'message' => $expiresAt ? 'Data de expiração definida!' : 'Expiração removida!',
                'expires_at' => $expiresAt ? $expiresAt->format('d/m/Y H:i') : null
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro: ' . $e->getMessage()
            ], 500);
        }
    }
}
