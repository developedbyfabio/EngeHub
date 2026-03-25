<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ip_address',
        'data_center_id',
        'description',
        'webmin_url',
        'nginx_url',
        'operating_system',
        'server_group_id',
        'logo_path',
        'monitor_status',
        'status',
        'last_status_check',
        'response_time'
    ];

    protected $casts = [
        'monitor_status' => 'boolean',
        'last_status_check' => 'datetime',
        'response_time' => 'integer'
    ];

    /**
     * Relacionamento com DataCenter
     */
    public function dataCenter()
    {
        return $this->belongsTo(DataCenter::class);
    }

    /**
     * Relacionamento com ServerGroup
     */
    public function serverGroup()
    {
        return $this->belongsTo(ServerGroup::class);
    }

    /**
     * Accessor para URL da logo
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo_path) {
            return Storage::disk('public')->url($this->logo_path);
        }
        return null;
    }

    /**
     * Verifica se o servidor está online via ping
     */
    public function checkStatus()
    {
        if (!$this->monitor_status) {
            return false;
        }

        $startTime = microtime(true);
        
        try {
            return $this->checkPingStatus($startTime);
        } catch (\Exception $e) {
            $this->updateStatus('offline', null);
            return false;
        }
    }

    /**
     * Verifica status via ping
     */
    private function checkPingStatus($startTime)
    {
        $ip = $this->ip_address;
        
        // Validar se é um IP válido
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $this->updateStatus('offline', null);
            return false;
        }

        // Executar ping (Linux/Unix)
        $command = "ping -c 1 -W 5 " . escapeshellarg($ip) . " 2>/dev/null";
        $result = shell_exec($command);
        
        if ($result && strpos($result, '1 received') !== false) {
            $endTime = microtime(true);
            $responseTime = round(($endTime - $startTime) * 1000);
            $this->updateStatus('online', $responseTime);
            return true;
        } else {
            $this->updateStatus('offline', null);
            return false;
        }
    }

    /**
     * Atualiza o status do servidor
     */
    private function updateStatus($status, $responseTime = null)
    {
        $this->update([
            'status' => $status,
            'last_status_check' => now(),
            'response_time' => $responseTime
        ]);
    }

    /**
     * Retorna a classe CSS para o indicador de status
     */
    public function getStatusClassAttribute()
    {
        switch ($this->status) {
            case 'online':
                return 'bg-green-500';
            case 'offline':
                return 'bg-red-500';
            default:
                return 'bg-gray-500';
        }
    }

    /**
     * Retorna o texto do status
     */
    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case 'online':
                return 'Online';
            case 'offline':
                return 'Offline';
            default:
                return 'Desconhecido';
        }
    }

    /**
     * Scope para agrupar servidores por grupo
     */
    public function scopeGrouped($query)
    {
        return $query->orderBy('group_name')
                    ->orderBy('name');
    }

    /**
     * Scope para servidores ativos de monitoramento
     */
    public function scopeMonitored($query)
    {
        return $query->where('monitor_status', true);
    }
}
