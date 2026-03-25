<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemLogin;
use App\Models\Card;
use App\Models\SystemUser;
use App\Models\SystemLoginPermission;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SystemLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Não usado no nosso caso, mas necessário para resource controller
        return response()->json(['message' => 'Not implemented']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $cardId = $request->query('card_id');
        
        if (!$cardId) {
            return response()->json(['error' => 'Card ID é obrigatório'], 400);
        }

        // Retornar a view de criação
        return view('admin.system-logins.create', compact('cardId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validar dados
            $request->validate([
                'card_id' => 'required|exists:cards,id',
                'title' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'password' => 'required|string|min:1',
                'notes' => 'nullable|string',
                'is_active' => 'boolean'
            ]);

            // Criar o login
            $systemLogin = SystemLogin::create([
                'card_id' => $request->card_id,
                'title' => $request->title,
                'username' => $request->username,
                'password' => $request->password,
                'notes' => $request->notes,
                'is_active' => $request->boolean('is_active', true)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login criado com sucesso!',
                'data' => $systemLogin
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . implode(', ', array_flatten($e->errors())),
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar login: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SystemLogin $systemLogin)
    {
        // Não usado no nosso caso, mas necessário para resource controller
        return response()->json(['message' => 'Not implemented']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SystemLogin $systemLogin): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $systemLogin
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SystemLogin $systemLogin): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:1',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $systemLogin->update([
            'title' => $request->title,
            'username' => $request->username,
            'password' => $request->password,
            'notes' => $request->notes,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login atualizado com sucesso!',
            'data' => $systemLogin
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SystemLogin $systemLogin): JsonResponse
    {
        $systemLogin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Login excluído com sucesso!'
        ]);
    }

    /**
     * Toggle password visibility
     */
    public function togglePassword(SystemLogin $systemLogin): JsonResponse
    {
        // Verificar se o usuário tem permissão para ver senhas
        $hasPermission = false;
        
        // Verificar se é usuário admin com permissão para ver senhas
        if (auth()->check() && auth()->user()->canViewPasswords()) {
            $hasPermission = true;
        }
        // Verificar se é usuário system com acesso a este card específico
        elseif (auth()->guard('system')->check() && auth()->guard('system')->user()->canViewSystem($systemLogin->card_id)) {
            $hasPermission = true;
        }
        
        if (!$hasPermission) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para visualizar senhas.'
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'password' => $systemLogin->password
        ]);
    }

    /**
     * Exibe o modal de permissões para um login específico
     */
    public function permissions(SystemLogin $systemLogin): JsonResponse
    {
        \Log::info('=== DEBUG: SystemLoginController::permissions chamado ===');
        \Log::info('SystemLogin ID: ' . $systemLogin->id);
        \Log::info('SystemLogin Title: ' . $systemLogin->title);
        
        try {
            // Buscar todos os usuários do sistema (da tabela users, não system_users)
            $systemUsers = \App\Models\User::where('id', '!=', 1)->get(); // Excluir admin principal
            \Log::info('Usuários encontrados: ' . $systemUsers->count());
            
            // Buscar usuários que já têm permissão para este login
            $usersWithPermission = $systemLogin->getUsersWithAccess();
            // Mapear system_user_id para user_id
            $userIdsWithPermission = [];
            foreach ($usersWithPermission as $systemUser) {
                if ($systemUser->user_id) {
                    $userIdsWithPermission[] = $systemUser->user_id;
                }
            }
            \Log::info('Usuários com permissão: ' . implode(', ', $userIdsWithPermission));

            $html = view('admin.system-logins.permissions', compact('systemLogin', 'systemUsers', 'userIdsWithPermission'))->render();
            \Log::info('HTML gerado com sucesso, tamanho: ' . strlen($html));

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro em permissions: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar permissões: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualiza as permissões de um login específico
     */
    public function updatePermissions(Request $request, SystemLogin $systemLogin): JsonResponse
    {
        \Log::info('=== DEBUG: SystemLoginController::updatePermissions chamado ===');
        \Log::info('SystemLogin ID: ' . $systemLogin->id);
        \Log::info('Request data: ' . json_encode($request->all()));
        
        try {
            $request->validate([
                'user_ids' => 'array',
                'user_ids.*' => 'exists:users,id'
            ]);

            // Remover todas as permissões existentes
            $systemLogin->permissions()->delete();
            \Log::info('Permissões antigas removidas');
            
            // Adicionar novas permissões
            $userIds = $request->input('user_ids', []);
            \Log::info('Novos user_ids: ' . implode(', ', $userIds));
            
            foreach ($userIds as $userId) {
                // Buscar o usuário da tabela users
                $user = \App\Models\User::find($userId);
                if (!$user) {
                    \Log::warning('Usuário não encontrado: ' . $userId);
                    continue;
                }
                
                // Buscar ou criar o SystemUser correspondente
                $systemUser = \App\Models\SystemUser::where('user_id', $userId)->first();
                if (!$systemUser) {
                    // Criar SystemUser se não existir
                    $systemUser = \App\Models\SystemUser::create([
                        'name' => $user->name,
                        'username' => $user->username ?: '',
                        'password' => 'N/A', // Senha não é necessária para permissões
                        'is_active' => true,
                        'user_id' => $userId
                    ]);
                    \Log::info('SystemUser criado para user_id: ' . $userId);
                }
                
                // Criar a permissão
                SystemLoginPermission::create([
                    'system_login_id' => $systemLogin->id,
                    'system_user_id' => $systemUser->id,
                    'is_active' => true
                ]);
                \Log::info('Permissão criada para system_user_id: ' . $systemUser->id);
            }

            \Log::info('Permissões atualizadas com sucesso');

            return response()->json([
                'success' => true,
                'message' => 'Permissões atualizadas com sucesso!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro em updatePermissions: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar permissões: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtém os logins filtrados por permissão do usuário atual
     */
    public function getFilteredLogins(Card $card): JsonResponse
    {
        // Verificar se o usuário tem permissão para ver senhas
        $hasPermission = false;
        
        if (auth()->check() && auth()->user()->canViewPasswords()) {
            $hasPermission = true;
        } elseif (auth()->guard('system')->check() && auth()->guard('system')->user()->canViewSystem($card->id)) {
            $hasPermission = true;
        }
        
        if (!$hasPermission) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para acessar os logins deste sistema.'
            ], 403);
        }

        // Buscar logins do card
        $systemLogins = $card->systemLogins()->orderBy('title')->get();

        // Aplicar filtro granular apenas para usuários comuns (não administradores)
        if (auth()->check()) {
            $userId = auth()->id();
            $user = auth()->user();
            
            // Verificar se é administrador (tem full_access)
            $isAdmin = $user->isAdmin();
            
            if (!$isAdmin) {
                $systemLogins = $systemLogins->filter(function ($login) use ($userId) {
                    return $login->canUserView($userId);
                });
            }
        } elseif (auth()->guard('system')->check()) {
            $systemUserId = auth()->guard('system')->id();
            $systemLogins = $systemLogins->filter(function ($login) use ($systemUserId) {
                return $login->canUserView($systemUserId);
            });
        }

        return response()->json([
            'success' => true,
            'logins' => $systemLogins->values()
        ]);
    }
}
