<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'permission_type',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Tipos de permissão disponíveis
    const VIEW_PASSWORDS = 'view_passwords';
    const MANAGE_SYSTEM_USERS = 'manage_system_users';
    const FULL_ACCESS = 'full_access';

    public function hasPermission($permissionType)
    {
        return $this->permission_type === $permissionType && $this->is_active;
    }
}
