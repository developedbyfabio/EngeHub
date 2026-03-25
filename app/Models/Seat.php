<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'network_map_id',
        'code',
        'setor',
        'observacoes',
    ];

    /**
     * Relacionamento: mesa pertence a um mapa
     */
    public function networkMap()
    {
        return $this->belongsTo(NetworkMap::class);
    }

    /**
     * Relacionamento: mesa tem muitos pontos de rede
     */
    public function networkPoints()
    {
        return $this->hasMany(SeatNetworkPoint::class);
    }

    /**
     * Relacionamento: mesa tem muitas atribuições (histórico)
     */
    public function assignments()
    {
        return $this->hasMany(SeatAssignment::class)->orderBy('started_at', 'desc');
    }

    /**
     * Relacionamento: atribuição atual (mais recente sem ended_at)
     */
    public function currentAssignment()
    {
        return $this->hasOne(SeatAssignment::class)
            ->whereNull('ended_at')
            ->latest('started_at');
    }

    /**
     * Accessor: colaborador atual
     */
    public function getCurrentUserAttribute()
    {
        return $this->currentAssignment?->user;
    }

    /**
     * Verifica se a mesa está ocupada
     */
    public function isOccupied()
    {
        return $this->currentAssignment !== null;
    }

    /**
     * Verifica se a mesa está disponível
     */
    public function isAvailable()
    {
        return !$this->isOccupied();
    }

    /**
     * Atribui um colaborador à mesa
     */
    public function assignUser($userId, $computerName = null, $reason = null)
    {
        // Finalizar atribuição anterior se existir
        if ($current = $this->currentAssignment) {
            $current->update([
                'ended_at' => now(),
                'reason' => $reason ?? 'Realocação'
            ]);
        }

        // Criar nova atribuição
        return $this->assignments()->create([
            'user_id' => $userId,
            'computer_name' => $computerName,
            'started_at' => now(),
            'reason' => $reason,
        ]);
    }

    /**
     * Atribui um colaborador à mesa por nome (texto livre, sem user_id).
     */
    public function assignCollaborator($collaboratorName, $computerName = null, $reason = null)
    {
        if ($current = $this->currentAssignment) {
            $current->update([
                'ended_at' => now(),
                'reason' => $reason ?? 'Realocação',
            ]);
        }
        return $this->assignments()->create([
            'user_id' => null,
            'collaborator_name' => $collaboratorName ? trim($collaboratorName) : null,
            'computer_name' => $computerName,
            'started_at' => now(),
            'reason' => $reason,
        ]);
    }

    /**
     * Libera a mesa (finaliza atribuição atual)
     */
    public function release($reason = null)
    {
        if ($current = $this->currentAssignment) {
            $current->update([
                'ended_at' => now(),
                'reason' => $reason ?? 'Mesa liberada'
            ]);
            return true;
        }
        return false;
    }
}
