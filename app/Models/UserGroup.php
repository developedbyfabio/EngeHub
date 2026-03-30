<?php

namespace App\Models;

use App\Support\NavPermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserGroup extends Model
{
    public const SLUG_ADMINISTRADORES = 'administradores';

    public const SLUG_USUARIOS = 'usuarios';

    protected $fillable = [
        'name',
        'slug',
        'full_access',
        'is_system',
        'nav_permissions',
    ];

    protected $casts = [
        'full_access' => 'boolean',
        'is_system' => 'boolean',
        'nav_permissions' => 'array',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'card_user_group')->withTimestamps();
    }

    public function allowsNavKey(string $key): bool
    {
        if ($this->full_access) {
            return true;
        }

        $perms = $this->nav_permissions ?? [];

        return ! empty($perms[$key]);
    }

    /**
     * @param  array<string, bool>  $checkboxes
     */
    public static function normalizePermissionsFromInput(array $checkboxes): array
    {
        $out = [];
        foreach (NavPermission::allKeys() as $k) {
            $out[$k] = ! empty($checkboxes[$k]);
        }

        return $out;
    }
}
