<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'link',
        'tab_id',
        'category_id',
        'data_center_id',
        'icon',
        'custom_icon_path',
        'file_path',
        'monitor_status',
        'monitoring_type',
        'status',
        'last_status_check',
        'response_time'
    ];

    protected $casts = [
        'monitor_status' => 'boolean',
        'last_status_check' => 'datetime',
        'response_time' => 'integer'
    ];

    public function tab()
    {
        return $this->belongsTo(Tab::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function dataCenter()
    {
        return $this->belongsTo(DataCenter::class);
    }

    /**
     * Relacionamento muitos-para-muitos com SystemUser
     */
    public function systemUsers()
    {
        return $this->belongsToMany(SystemUser::class, 'system_user_cards')
                    ->withTimestamps();
    }

    /**
     * Relacionamento com SystemLogin (logins e senhas do sistema)
     */
    public function systemLogins()
    {
        return $this->hasMany(SystemLogin::class);
    }

    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return Storage::disk('public')->url($this->file_path);
        }
        return null;
    }

    public function getCustomIconUrlAttribute()
    {
        if ($this->custom_icon_path) {
            return Storage::disk('public')->url($this->custom_icon_path);
        }
        return null;
    }

    /**
     * Verifica se o card está online
     */
    public function checkStatus()
    {
        if (!$this->monitor_status) {
            return false;
        }

        $startTime = microtime(true);
        
        try {
            // Se monitoring_type é 'ping', usar ping; caso contrário, usar HTTP (padrão)
            if ($this->monitoring_type === 'ping') {
                return $this->checkPingStatus($startTime);
            } else {
                // Para cards existentes sem monitoring_type definido, usar HTTP
                return $this->checkHttpStatus($startTime);
            }
        } catch (\Exception $e) {
            $this->updateStatus('offline', null);
            return false;
        }
    }

    /**
     * Verifica status via ping para IPs
     */
    private function checkPingStatus($startTime)
    {
        $ip = $this->link;
        
        // Remover protocolo se existir
        $ip = preg_replace('/^https?:\/\//', '', $ip);
        $ip = preg_replace('/\/.*$/', '', $ip);
        
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
     * Verifica status via HTTP para URLs
     */
    private function checkHttpStatus($startTime)
    {
        $url = $this->link;
        
        // Configurar timeout para 10 segundos
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'user_agent' => 'EngeHub-Status-Checker/1.0'
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false
            ]
        ]);

        // Tentar fazer uma requisição HEAD primeiro (mais rápido)
        $headers = @get_headers($url, 1, $context);
        
        if ($headers === false) {
            // Se HEAD falhar, tentar GET
            $response = @file_get_contents($url, false, $context);
            if ($response === false) {
                $this->updateStatus('offline', null);
                return false;
            }
        }

        $endTime = microtime(true);
        $responseTime = round(($endTime - $startTime) * 1000); // Converter para milissegundos

        $this->updateStatus('online', $responseTime);
        return true;
    }

    /**
     * Atualiza o status do card
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
     * Relacionamento com favoritos
     */
    public function favorites()
    {
        return $this->hasMany(UserFavorite::class);
    }

    /**
     * Relacionamento many-to-many com usuários que favoritaram
     */
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_favorites')
                    ->withTimestamps();
    }

    /**
     * Relacionamento many-to-many com system users que favoritaram
     */
    public function favoritedBySystemUsers()
    {
        return $this->belongsToMany(SystemUser::class, 'user_favorites', 'card_id', 'system_user_id')
                    ->withTimestamps();
    }

    /**
     * Verifica se o card é favorito de um usuário específico
     */
    public function isFavoritedBy($userId = null, $systemUserId = null)
    {
        return UserFavorite::isFavorite($this->id, $userId, $systemUserId);
    }

    /**
     * Conta total de favoritos
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites()->count();
    }
} 