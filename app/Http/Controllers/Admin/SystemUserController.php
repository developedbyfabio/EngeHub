<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\UserPermission;
use App\Models\SystemUser;
use App\Support\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SystemUserController extends Controller
{
    /**
     * Exibe a lista de usuários dos sistemas
     */
    public function index()
    {
        $users = User::with('userGroup')->orderBy('id')->get();

        $userGroups = UserGroup::orderBy('name')->get();

        return view('admin.system-users.index', compact('users', 'userGroups'));
    }

    /**
     * Exibe o formulário de criação
     */
    public function create()
    {
        $groups = UserGroup::orderBy('name')->get();
        if (request()->ajax() || request()->wantsJson()) {
            return view('admin.system-users.create', compact('groups'));
        }

        return view('admin.system-users.create', compact('groups'));
    }

    /**
     * Armazena um novo usuário
     */
    public function store(Request $request)
    {
        $adminGroupId = UserGroup::where('slug', UserGroup::SLUG_ADMINISTRADORES)->value('id');

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'is_admin' => 'boolean',
            'user_group_id' => 'required|exists:user_groups,id',
            'enabled_services' => 'nullable|array',
            'enabled_services.*' => ['string', Rule::in(UserService::allKeys())],
        ]);

        try {
            $isAdmin = $request->boolean('is_admin');
            $userGroupId = $isAdmin && $adminGroupId
                ? (int) $adminGroupId
                : (int) $request->input('user_group_id');

            // Criar usuário do Laravel para login no sistema
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->username . '@engepecas.com',
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
                'user_group_id' => $userGroupId,
                'enabled_services' => $isAdmin ? null : $this->normalizeEnabledServices($request),
            ]);

            // Criar permissões baseado no tipo de usuário
            
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
        $groups = UserGroup::orderBy('name')->get();

        return view('admin.system-users.edit', compact('user', 'groups'));
    }

    /**
     * Atualiza o usuário
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6',
            'user_group_id' => 'required|exists:user_groups,id',
            'enabled_services' => 'nullable|array',
            'enabled_services.*' => ['string', Rule::in(UserService::allKeys())],
        ]);

        try {
            $groupId = (int) $request->input('user_group_id');
            $group = UserGroup::find($groupId);

            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->username . '@engepecas.com',
                'user_group_id' => $groupId,
            ];

            if ($group && ! $group->full_access) {
                $data['enabled_services'] = $this->normalizeEnabledServices($request);
            }

            // Só atualiza a senha se foi fornecida
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);
            $user->refresh();
            if ($user->userGroup) {
                $this->syncLegacyUserPermissionsFromGroup($user, $user->userGroup);
                $user->refresh();
            }
            if ($user->userGroup?->full_access || $user->hasFullAccess()) {
                $user->forceFill(['enabled_services' => null])->saveQuietly();
            }

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
        if ($user->id === 1) {
            $msg = 'A conta principal do sistema (id 1) não pode ser removida.';
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }

            return redirect()->route('admin.system-users.index')->with('error', $msg);
        }

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
     * Mantém user_permissions alinhado ao grupo (legado: isAdmin(), cartões, logins).
     */
    /**
     * Lista normalizada de serviços habilitados. Vazio = nenhum; todos os conhecidos = null (equivalente a “todos”).
     *
     * @return list<string>|null
     */
    private function normalizeEnabledServices(Request $request): ?array
    {
        $raw = $request->input('enabled_services', []);
        if (! is_array($raw)) {
            $raw = [];
        }
        $valid = UserService::allKeys();
        $picked = array_values(array_unique(array_intersect($raw, $valid)));
        if (count($picked) >= count($valid)) {
            return null;
        }

        return $picked;
    }

    private function syncLegacyUserPermissionsFromGroup(User $user, UserGroup $group): void
    {
        $user->userPermissions()->delete();

        if ($group->full_access) {
            UserPermission::create([
                'user_id' => $user->id,
                'permission_type' => UserPermission::VIEW_PASSWORDS,
                'is_active' => true,
            ]);
            UserPermission::create([
                'user_id' => $user->id,
                'permission_type' => UserPermission::MANAGE_SYSTEM_USERS,
                'is_active' => true,
            ]);
            UserPermission::create([
                'user_id' => $user->id,
                'permission_type' => UserPermission::FULL_ACCESS,
                'is_active' => true,
            ]);

            return;
        }

        UserPermission::create([
            'user_id' => $user->id,
            'permission_type' => UserPermission::VIEW_PASSWORDS,
            'is_active' => true,
        ]);
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
