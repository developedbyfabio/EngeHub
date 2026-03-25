<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Relacionamento com servidores
     */
    public function servers()
    {
        return $this->hasMany(Server::class);
    }

    /**
     * Scope para grupos ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para ordenação
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Accessor para contagem de servidores
     */
    public function getServersCountAttribute()
    {
        return $this->servers()->count();
    }

    /**
     * Accessor para contagem de servidores ativos
     */
    public function getActiveServersCountAttribute()
    {
        return $this->servers()->where('monitor_status', true)->count();
    }
}