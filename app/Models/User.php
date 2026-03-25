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
    ];

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