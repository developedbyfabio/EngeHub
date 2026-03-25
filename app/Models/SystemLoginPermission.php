<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLoginPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_login_id',
        'system_user_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Relacionamento com SystemLogin
     */
    public function systemLogin()
    {
        return $this->belongsTo(SystemLogin::class);
    }

    /**
     * Relacionamento com SystemUser
     */
    public function systemUser()
    {
        return $this->belongsTo(SystemUser::class);
    }

    /**
     * Scope para permissões ativas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}