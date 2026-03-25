<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatNetworkPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'seat_id',
        'code',
        'mac_address',
        'ip',
        'observacoes',
    ];

    /**
     * Relacionamento: ponto de rede pertence a uma mesa
     */
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    /**
     * Formata o MAC Address para exibição
     */
    public function getFormattedMacAttribute()
    {
        if (!$this->mac_address) {
            return null;
        }
        
        // Remove caracteres especiais
        $mac = preg_replace('/[^0-9A-Fa-f]/', '', $this->mac_address);
        
        // Formata como XX:XX:XX:XX:XX:XX
        return strtoupper(implode(':', str_split($mac, 2)));
    }

    /**
     * Valida se o IP é válido
     */
    public function hasValidIp()
    {
        return filter_var($this->ip, FILTER_VALIDATE_IP) !== false;
    }
}
