<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Camera extends Model
{
    use HasFactory;

    const STATUS_ATIVO = 'ativo';
    const STATUS_INATIVO = 'inativo';
    const STATUS_AGUARDANDO_CORRECAO = 'aguardando_correcao';

    protected $fillable = [
        'dvr_id',
        'nome',
        'descricao',
        'foto',
        'canal',
        'status',
        'ordem',
    ];

    protected $attributes = [
        'status' => self::STATUS_ATIVO,
    ];

    public function dvr()
    {
        return $this->belongsTo(Dvr::class);
    }

    public function checklistItens()
    {
        return $this->hasMany(CameraChecklistItem::class)->orderByDesc('created_at');
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto) {
            return null;
        }
        return Storage::disk('public')->url($this->foto);
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_ATIVO => 'Ativo',
            self::STATUS_INATIVO => 'Inativo',
            self::STATUS_AGUARDANDO_CORRECAO => 'Aguardando correção',
        ];
    }
}
