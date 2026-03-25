<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CameraChecklistItem extends Model
{
    use HasFactory;

    const STATUS_ONLINE = 'online';
    const STATUS_OFFLINE = 'offline';
    const STATUS_COM_ALERTA = 'com_alerta';
    const STATUS_NAO_VERIFICADA = 'nao_verificada';

    const ACAO_PENDENTE = 'pendente';
    const ACAO_EM_ANDAMENTO = 'em_andamento';
    const ACAO_RESOLVIDO = 'resolvido';

    protected $table = 'camera_checklist_itens';

    protected $fillable = [
        'camera_checklist_id',
        'camera_id',
        'status_operacional',
        'online',
        'angulo_correto',
        'gravando',
        'problema',
        'descricao_problema',
        'acao_corretiva_necessaria',
        'acao_corretiva_realizada',
        'status_acao',
        'motivo_nao_resolvido',
        'observacao',
        'evidencia_path',
    ];

    protected $casts = [
        'online' => 'boolean',
        'angulo_correto' => 'boolean',
        'gravando' => 'boolean',
        'problema' => 'boolean',
    ];

    protected $attributes = [
        'status_operacional' => self::STATUS_NAO_VERIFICADA,
    ];

    public function cameraChecklist()
    {
        return $this->belongsTo(CameraChecklist::class);
    }

    public function camera()
    {
        return $this->belongsTo(Camera::class);
    }

    public function getEvidenciaUrlAttribute(): ?string
    {
        if (!$this->evidencia_path) {
            return null;
        }
        return Storage::disk('public')->url($this->evidencia_path);
    }

    public static function statusOperacionalOptions(): array
    {
        return [
            self::STATUS_ONLINE => 'Online',
            self::STATUS_OFFLINE => 'Offline',
            self::STATUS_COM_ALERTA => 'Com Alerta',
            self::STATUS_NAO_VERIFICADA => 'Não Verificada',
        ];
    }

    public static function statusAcaoOptions(): array
    {
        return [
            self::ACAO_PENDENTE => 'Pendente',
            self::ACAO_EM_ANDAMENTO => 'Em Andamento',
            self::ACAO_RESOLVIDO => 'Resolvido',
        ];
    }
}
