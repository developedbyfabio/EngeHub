<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dvr extends Model
{
    use HasFactory;

    const STATUS_ATIVO = 'ativo';
    const STATUS_INATIVO = 'inativo';

    protected $fillable = [
        'nome',
        'descricao',
        'localizacao',
        'acesso_web',
        'status',
    ];

    protected $attributes = [
        'status' => self::STATUS_ATIVO,
    ];

    public function cameras()
    {
        return $this->hasMany(Camera::class)->orderBy('ordem')->orderBy('created_at');
    }

    public function cameraChecklists()
    {
        return $this->hasMany(CameraChecklist::class, 'dvr_id');
    }

    public function camerasAtivas()
    {
        return $this->hasMany(Camera::class)->where('status', self::STATUS_ATIVO)->orderBy('ordem')->orderBy('created_at');
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_ATIVO => 'Ativo',
            self::STATUS_INATIVO => 'Inativo',
        ];
    }
}
