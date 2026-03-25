<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CameraChecklistAnexo extends Model
{
    use HasFactory;

    protected $fillable = [
        'camera_checklist_id',
        'dvr_id',
        'camera_id',
        'caminho_arquivo',
        'nome_original',
        'tipo_arquivo',
    ];

    public function cameraChecklist()
    {
        return $this->belongsTo(CameraChecklist::class);
    }

    public function dvr()
    {
        return $this->belongsTo(\App\Models\Dvr::class);
    }

    public function camera()
    {
        return $this->belongsTo(\App\Models\Camera::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->caminho_arquivo);
    }
}
