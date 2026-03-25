<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPermission;
use App\Models\Card;
use App\Models\SystemUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SystemUserController extends Controller
{
    /**
     * Exibe a lista de usuários dos sistemas
     */
    public function index()
    {
        $users = User::where('id', '!=', 1)->get(); // Excluir o admin principal
        
        return view('admin.system-users.index', compact('users'));
    }

    /**
     * Exibe o formulário de criação
     */
    public function create()
    {
        if (request()->ajax() || request()->wantsJson()) {
            return view('admin.system-users.create');
        }
        
        return view('admin.system-users.create');
    }

    /**
     * Armazena um novo usuário
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'is_admin' => 'boolean'
        ]);

        try {
            // Criar usuário do Laravel para login no sistema
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->username . '@engepecas.com',
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            // Criar permissões baseado no tipo de usuário
            $isAdmin = $request->boolean('is_admin');
            
            if ($isAdmin) {
                // Usuário Administrador - todas as permissões
                UserPermission::create([
                    'user_id' => $user->id,
                    'permission_type' => UserPermission::VIEW_PASSWORDS,
                    'is_active' => true
                ]);
                UserPermission::create([
                    'user_id' => $user->id,
                    'permission_type' => UserPermission::MANAGE_SYSTEM_USERS,
                    'is_active' => true
                ]);
                UserPermission::create([
                    'user_id' => $user->id,
                    'permission_type' => UserPermission::FULL_ACCESS,
                    'is_active' => true
                ]);
            } else {
                // Usuário Comum - apenas view_passwords
                UserPermission::create([
                    'user_id' => $user->id,
                    'permission_type' => UserPermission::VIEW_PASSWORDS,
                    'is_active' => true
                ]);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuário criado com sucesso! Agora ele pode fazer login no sistema com o email: ' . $request->username . '@engepecas.com'
                ]);
            }
            
            return redirect()->route('admin.system-users.index')
                           ->with('success', 'Usuário criado com sucesso! Agora ele pode fazer login no sistema.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erro ao criar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Exibe o formulário de edição
     */
    public function edit(User $user)
    {
        return view('admin.system-users.edit', compact('user'));
    }

    /**
     * Atualiza o usuário
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6'
        ]);

        try {
            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->username . '@engepecas.com'
            ];

            // Só atualiza a senha se foi fornecida
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuário atualizado com sucesso!'
                ]);
            }
            
            return redirect()->route('admin.system-users.index')
                           ->with('success', 'Usuário atualizado com sucesso!');

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar usuário: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withInput()->with('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Remove o usuário
     */
    public function destroy(User $user)
    {
        try {
            // Remove as permissões do usuário
            $user->userPermissions()->delete();
            
            // Remove o usuário
            $user->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuário removido com sucesso!'
                ]);
            }
            
            return redirect()->route('admin.system-users.index')
                           ->with('success', 'Usuário removido com sucesso!');

        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao remover usuário: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Erro ao remover usuário: ' . $e->getMessage());
        }
    }


    /**
     * Exibe o formulário de permissões do usuário
     */
    public function permissions(User $user)
    {
        // Sistema simplificado: apenas Administrador ou Usuário Comum
        $isAdmin = $user->isAdmin();
        
        $permissions = [
            'is_admin' => [
                'label' => 'Administrador',
                'description' => 'Acesso total ao sistema administrativo (gerenciar usuários, ver todos os logins, editar, excluir, etc.)',
                'active' => $isAdmin
            ],
            'is_user' => [
                'label' => 'Usuário Comum',
                'description' => 'Acesso restrito apenas aos logins com permissão específica (não pode editar, gerenciar ou excluir)',
                'active' => !$isAdmin
            ]
        ];

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'html' => view('admin.system-users.permissions', compact('user', 'permissions'))->render()
            ]);
        }
        
        return view('admin.system-users.permissions', compact('user', 'permissions'));
    }

    /**
     * Atualiza as permissões do usuário
     */
    public function updatePermissions(Request $request, User $user)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'in:is_admin,is_user'
        ]);

        try {
            // Remove todas as permissões atuais
            $user->userPermissions()->delete();
            
            // Adiciona as novas permissões baseadas na seleção
            $permissions = $request->input('permissions', []);
            
            if (in_array('is_admin', $permissions)) {
                // Se é administrador, adiciona todas as permissões
                UserPermission::create([
                    'user_id' => $user->id,
                    'permission_type' => UserPermission::VIEW_PASSWORDS,
                    'is_active' => true
                ]);
                UserPermission::create([
                    'user_id' => $user->id,
                    'permission_type' => UserPermission::MANAGE_SYSTEM_USERS,
                    'is_active' => true
                ]);
                UserPermission::create([
                    'user_id' => $user->id,
                    'permission_type' => UserPermission::FULL_ACCESS,
                    'is_active' => true
                ]);
            } else {
                // Se é usuário comum, adiciona apenas permissão para ver senhas
                UserPermission::create([
                    'user_id' => $user->id,
                    'permission_type' => UserPermission::VIEW_PASSWORDS,
                    'is_active' => true
                ]);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permissões atualizadas com sucesso!'
                ]);
            }
            
            return redirect()->route('admin.system-users.index')
                           ->with('success', 'Permissões atualizadas com sucesso!');
                           
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar permissões: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Erro ao atualizar permissões: ' . $e->getMessage());
        }
    }

    /**
     * Exibe o gerenciamento de URL secreta do usuário
     */
    public function showSecretUrl(User $user)
    {
        // Buscar ou criar SystemUser associado
        $systemUser = SystemUser::where('user_id', $user->id)->first();
        
        if (!$systemUser) {
            // Criar SystemUser se não existir
            $systemUser = SystemUser::create([
                'name' => $user->name,
                'username' => $user->username ?: $user->email,
                'password' => 'N/A',
                'is_active' => true,
                'user_id' => $user->id
            ]);
        }
        
        // Gerar URL secreta se não existir
        if (!$systemUser->secret_url) {
            $systemUser->generateSecretUrl();
            $systemUser->refresh();
        }
        
        // Carregar logs de acesso recentes (últimos 50)
        $recentLogs = $systemUser->secretUrlAccessLogs()
            ->orderBy('accessed_at', 'desc')
            ->limit(50)
            ->get();
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'html' => view('admin.system-users.secret-url', compact('user', 'systemUser', 'recentLogs'))->render()
            ]);
        }
        
        return view('admin.system-users.secret-url', compact('user', 'systemUser', 'recentLogs'));
    }

    /**
     * Regenera a URL secreta do usuário
     */
    public function regenerateSecretUrl(User $user)
    {
        try {
            $systemUser = SystemUser::where('user_id', $user->id)->first();
            
            if (!$systemUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'SystemUser não encontrado'
                ], 404);
            }
            
            $oldUrl = $systemUser->secret_url;
            $newUrl = $systemUser->regenerateSecretUrl();
            
            \Log::info('URL secreta regenerada', [
                'user_id' => $user->id,
                'system_user_id' => $systemUser->id,
                'old_url' => $oldUrl,
                'new_url' => $newUrl
            ]);
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'URL secreta regenerada com sucesso!',
                    'secret_url' => $systemUser->full_secret_url
                ]);
            }
            
            return redirect()->route('admin.system-users.secret-url', $user)
                           ->with('success', 'URL secreta regenerada com sucesso!');
                           
        } catch (\Exception $e) {
            \Log::error('Erro ao regenerar URL secreta', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao regenerar URL secreta: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Erro ao regenerar URL secreta: ' . $e->getMessage());
        }
    }

    /**
     * Alterna o status de habilitação da URL secreta
     */
    public function toggleSecretUrl(User $user)
    {
        try {
            $systemUser = SystemUser::where('user_id', $user->id)->first();
            
            if (!$systemUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'SystemUser não encontrado'
                ], 404);
            }
            
            if ($systemUser->secret_url_enabled) {
                $systemUser->disableSecretUrl();
                $message = 'URL secreta desabilitada com sucesso!';
            } else {
                $systemUser->enableSecretUrl();
                $message = 'URL secreta habilitada com sucesso!';
            }
            
            \Log::info('Status de URL secreta alterado', [
                'user_id' => $user->id,
                'system_user_id' => $systemUser->id,
                'enabled' => $systemUser->secret_url_enabled
            ]);
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'enabled' => $systemUser->secret_url_enabled
                ]);
            }
            
            return redirect()->route('admin.system-users.secret-url', $user)
                           ->with('success', $message);
                           
        } catch (\Exception $e) {
            \Log::error('Erro ao alterar status de URL secreta', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao alterar status: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Erro ao alterar status: ' . $e->getMessage());
        }
    }

    /**
     * Define a data de expiração da URL secreta
     */
    public function setSecretUrlExpiration(User $user, Request $request)
    {
        try {
            $request->validate([
                'expires_at' => 'nullable|date|after:now'
            ]);
            
            $systemUser = SystemUser::where('user_id', $user->id)->first();
            
            if (!$systemUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'SystemUser não encontrado'
                ], 404);
            }
            
            $expiresAt = $request->input('expires_at') ? now()->parse($request->input('expires_at')) : null;
            
            $systemUser->update([
                'secret_url_expires_at' => $expiresAt
            ]);
            
            \Log::info('Data de expiração da URL secreta definida', [
                'user_id' => $user->id,
                'system_user_id' => $systemUser->id,
                'expires_at' => $expiresAt
            ]);
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $expiresAt ? 'Data de expiração definida com sucesso!' : 'Expiração removida com sucesso!',
                    'expires_at' => $expiresAt ? $expiresAt->format('d/m/Y H:i') : null
                ]);
            }
            
            return redirect()->route('admin.system-users.secret-url', $user)
                           ->with('success', $expiresAt ? 'Data de expiração definida com sucesso!' : 'Expiração removida com sucesso!');
                           
        } catch (\Exception $e) {
            \Log::error('Erro ao definir expiração da URL secreta', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao definir expiração: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Erro ao definir expiração: ' . $e->getMessage());
        }
    }
}
