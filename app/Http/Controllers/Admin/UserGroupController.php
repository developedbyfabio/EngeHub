<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserGroup;
use App\Support\NavPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserGroupController extends Controller
{
    public function index()
    {
        $groups = UserGroup::orderBy('name')->get();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'groups' => $groups->map(fn (UserGroup $g) => [
                    'id' => $g->id,
                    'name' => $g->name,
                    'slug' => $g->slug,
                    'full_access' => $g->full_access,
                    'is_system' => $g->is_system,
                    'nav_permissions' => $g->nav_permissions ?? [],
                ]),
                'labels' => NavPermission::labels(),
                'keys' => NavPermission::allKeys(),
            ]);
        }

        return response()->json($groups);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'full_access' => 'sometimes|boolean',
            'nav_permissions' => 'sometimes|array',
        ]);

        $fullAccess = $request->boolean('full_access');
        $perms = $fullAccess
            ? NavPermission::fullPermissionMap()
            : UserGroup::normalizePermissionsFromInput($request->input('nav_permissions', []));

        $base = Str::slug($request->name) ?: 'grupo';
        $slug = $base;
        $i = 0;
        while (UserGroup::where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }

        $group = UserGroup::create([
            'name' => $request->name,
            'slug' => $slug,
            'full_access' => $fullAccess,
            'is_system' => false,
            'nav_permissions' => $perms,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Grupo criado com sucesso.',
            'group' => $group,
        ]);
    }

    public function update(Request $request, UserGroup $userGroup)
    {
        if ($userGroup->slug === UserGroup::SLUG_ADMINISTRADORES) {
            return response()->json([
                'success' => false,
                'message' => 'O grupo Administradores não pode ser alterado.',
            ], 422);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'full_access' => 'sometimes|boolean',
            'nav_permissions' => 'sometimes|array',
        ]);

        $fullAccess = $request->has('full_access')
            ? $request->boolean('full_access')
            : $userGroup->full_access;

        $data = [
            'full_access' => $fullAccess,
        ];

        if ($request->has('name')) {
            $data['name'] = $request->name;
        }

        if ($fullAccess) {
            $data['nav_permissions'] = NavPermission::fullPermissionMap();
        } elseif ($request->has('nav_permissions')) {
            $data['nav_permissions'] = UserGroup::normalizePermissionsFromInput($request->input('nav_permissions', []));
        }

        $userGroup->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Grupo atualizado.',
            'group' => $userGroup->fresh(),
        ]);
    }

    public function destroy(Request $request, UserGroup $userGroup)
    {
        $request->validate([
            'delete_password' => 'required|string',
        ]);

        $expected = config('app.user_group_delete_password');
        if ($request->input('delete_password') !== $expected) {
            return response()->json([
                'success' => false,
                'message' => 'Senha incorreta. A exclusão não foi autorizada.',
            ], 422);
        }

        if ($userGroup->slug === UserGroup::SLUG_ADMINISTRADORES) {
            return response()->json([
                'success' => false,
                'message' => 'O grupo Administradores não pode ser excluído.',
            ], 422);
        }

        $blockingUsers = $userGroup->users()
            ->orderBy('id')
            ->get(['id', 'name', 'username']);

        if ($blockingUsers->isNotEmpty()) {
            $sample = $blockingUsers->take(5)->map(function ($u) {
                $label = $u->name;
                if ($u->username) {
                    $label .= ' ('.$u->username.')';
                }

                return $label;
            })->implode(', ');

            $suffix = $blockingUsers->count() > 5
                ? ' e outros'
                : '';

            return response()->json([
                'success' => false,
                'message' => 'Não é possível excluir: '.$blockingUsers->count().' '
                    .($blockingUsers->count() === 1 ? 'usuário ainda está' : 'usuários ainda estão')
                    .' neste grupo ('.$sample.$suffix.'). Altere o grupo de acesso deles e tente novamente.',
            ], 422);
        }

        $userGroup->delete();

        return response()->json(['success' => true, 'message' => 'Grupo removido.']);
    }
}
