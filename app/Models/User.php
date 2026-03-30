<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'user_group_id',
        'enabled_services',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'enabled_services' => 'array',
    ];

    /**
     * Usuário web atual ou User vinculado ao SystemUser autenticado (para permissões de serviço).
     */
    public static function currentForServices(): ?self
    {
        $u = auth()->user();
        if ($u instanceof self) {
            return $u;
        }
        $sys = auth()->guard('system')->user();
        if ($sys instanceof SystemUser && $sys->user_id) {
            return self::find($sys->user_id);
        }

        return null;
    }

    /**
     * Pode usar um serviço operacional (ex.: checklists em Câmeras).
     * null em enabled_services = compatível com registros antigos (todos os serviços).
     */
    public function canUseService(string $serviceKey): bool
    {
        if (! \App\Support\UserService::isValidKey($serviceKey)) {
            return false;
        }

        if ($this->hasFullAccess()) {
            return true;
        }

        if ($this->userGroup?->full_access) {
            return true;
        }

        $enabled = $this->enabled_services;
        if ($enabled === null) {
            return true;
        }

        return in_array($serviceKey, $enabled, true);
    }

    /**
     * Relacionamento com permissões do usuário
     */
    public function userPermissions()
    {
        return $this->hasMany(UserPermission::class);
    }

    /**
     * Relacionamento com SystemUser (para acesso aos cards)
     */
    public function systemUser()
    {
        return $this->hasOne(SystemUser::class);
    }

    public function userGroup()
    {
        return $this->belongsTo(UserGroup::class);
    }

    /**
     * Pode ver item de menu / rota principal ou admin conforme grupo.
     */
    public function canAccessNav(string $key): bool
    {
        if ($this->hasFullAccess()) {
            return true;
        }

        if ($this->userGroup) {
            return $this->userGroup->allowsNavKey($key);
        }

        return false;
    }

    public function canAccessAnyAdminNav(): bool
    {
        if ($this->hasFullAccess()) {
            return true;
        }

        if ($this->userGroup?->full_access) {
            return true;
        }

        foreach (\App\Support\NavPermission::adminKeys() as $k) {
            if ($this->canAccessNav($k)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Exibir o menu suspenso "Gerenciar" (pelo menos uma subárea liberada).
     */
    public function canSeeGerenciarMenu(): bool
    {
        return $this->canAccessAnyAdminNav();
    }

    /**
     * Verifica se o usuário tem uma permissão específica
     */
    public function hasUserPermission($permissionType)
    {
        return $this->userPermissions()
            ->where('permission_type', $permissionType)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Verifica se o usuário pode ver senhas
     */
    public function canViewPasswords()
    {
        return $this->hasUserPermission(UserPermission::VIEW_PASSWORDS) ||
               $this->hasUserPermission(UserPermission::FULL_ACCESS);
    }

    /**
     * Verifica se o usuário pode gerenciar usuários dos sistemas
     */
    public function canManageSystemUsers()
    {
        return $this->hasUserPermission(UserPermission::MANAGE_SYSTEM_USERS) ||
               $this->hasUserPermission(UserPermission::FULL_ACCESS);
    }

    /**
     * Verifica se o usuário tem acesso total
     */
    public function hasFullAccess()
    {
        return $this->hasUserPermission(UserPermission::FULL_ACCESS);
    }

    /**
     * Verifica se o usuário é realmente administrador (apenas full_access)
     */
    public function isAdmin()
    {
        return $this->hasUserPermission(UserPermission::FULL_ACCESS);
    }

    /**
     * Relacionamento com favoritos
     */
    public function favorites()
    {
        return $this->hasMany(UserFavorite::class);
    }

    /**
     * Relacionamento many-to-many com cards favoritos
     */
    public function favoriteCards()
    {
        return $this->belongsToMany(Card::class, 'user_favorites')
                    ->withTimestamps();
    }

    /**
     * Verifica se um card é favorito
     */
    public function isFavoriteCard($cardId)
    {
        return UserFavorite::isFavorite($cardId, $this->id, null);
    }

    /**
     * Adiciona card aos favoritos
     */
    public function addToFavorites($cardId)
    {
        return UserFavorite::addToFavorites($cardId, $this->id, null);
    }

    /**
     * Remove card dos favoritos
     */
    public function removeFromFavorites($cardId)
    {
        return UserFavorite::removeFromFavorites($cardId, $this->id, null);
    }
} 