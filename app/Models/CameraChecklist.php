<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CameraChecklist extends Model
{
    use HasFactory;

    const STATUS_EM_ANDAMENTO = 'em_andamento';
    const STATUS_FINALIZADO = 'finalizado';
    const STATUS_CANCELADO = 'cancelado';

    protected $fillable = [
        'dvr_id',
        'user_id',
        'responsavel_nome',
        'status',
        'iniciado_em',
        'finalizado_em',
        'observacoes_gerais',
    ];

    protected $casts = [
        'iniciado_em' => 'datetime',
        'finalizado_em' => 'datetime',
    ];

    protected $attributes = [
        'status' => self::STATUS_EM_ANDAMENTO,
    ];

    public function dvr()
    {
        return $this->belongsTo(Dvr::class);
    }

    public function dvrs()
    {
        return $this->belongsToMany(Dvr::class, 'camera_checklist_dvrs', 'camera_checklist_id', 'dvr_id')
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function itens()
    {
        return $this->hasMany(CameraChecklistItem::class, 'camera_checklist_id');
    }

    public function anexos()
    {
        return $this->hasMany(CameraChecklistAnexo::class, 'camera_checklist_id');
    }

    public function getResponsavelAttribute(): string
    {
        return $this->responsavel_nome ?? $this->user?->name ?? 'Sistema';
    }

    public function getTotalCamerasAttribute(): int
    {
        return $this->itens->count();
    }

    public function getOnlineCountAttribute(): int
    {
        return $this->itens->where('status_operacional', 'online')->count();
    }

    public function getOfflineCountAttribute(): int
    {
        return $this->itens->whereIn('status_operacional', ['offline', 'com_alerta'])->count();
    }

    public function getProblemaCountAttribute(): int
    {
        return $this->itens->where('problema', true)->count();
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_EM_ANDAMENTO => 'Em Andamento',
            self::STATUS_FINALIZADO => 'Finalizado',
            self::STATUS_CANCELADO => 'Cancelado',
        ];
    }
}
