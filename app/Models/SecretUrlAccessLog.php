<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecretUrlAccessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'system_user_id',
        'ip_address',
        'user_agent',
        'referer',
        'accessed_at'
    ];

    protected $casts = [
        'accessed_at' => 'datetime'
    ];

    /**
     * Relacionamento com SystemUser
     */
    public function systemUser()
    {
        return $this->belongsTo(SystemUser::class);
    }
}
