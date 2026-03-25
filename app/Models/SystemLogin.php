<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SystemLogin extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_id',
        'title',
        'username',
        'password',
        'notes',
        'is_active'
    ];

    protected $hidden = [
        // password removido para permitir visualização
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relacionamento com o Card
     */
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Relacionamento com permissões de usuários
     */
    public function permissions()
    {
        return $this->hasMany(SystemLoginPermission::class);
    }

    /**
     * Relacionamento many-to-many com SystemUser através de permissões
     */
    public function systemUsers()
    {
        return $this->belongsToMany(SystemUser::class, 'system_login_permissions')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    /**
     * Verifica se um usuário específico pode ver este login
     * Aceita tanto system_user_id quanto user_id
     */
    public function canUserView($userId)
    {
        // Primeiro, tentar encontrar por system_user_id direto
        $hasPermission = $this->permissions()
                    ->where('system_user_id', $userId)
                    ->where('is_active', true)
                    ->exists();
        
        if ($hasPermission) {
            return true;
        }
        
        // Se não encontrou, buscar pelo user_id através do SystemUser
        $systemUser = \App\Models\SystemUser::where('user_id', $userId)->first();
        if ($systemUser) {
            return $this->permissions()
                        ->where('system_user_id', $systemUser->id)
                        ->where('is_active', true)
                        ->exists();
        }
        
        return false;
    }

    /**
     * Obtém usuários que podem ver este login
     */
    public function getUsersWithAccess()
    {
        return $this->systemUsers()
                    ->wherePivot('is_active', true)
                    ->get();
    }

    /**
     * Armazenar senha sem hash (para visualização dos usuários)
     * Em produção, considere usar criptografia reversível ou um campo separado
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value;
    }

    /**
     * Scope para logins ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
