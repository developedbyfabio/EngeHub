<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFavorite extends Model
{
    use HasFactory;

    protected $table = 'user_favorites';

    protected $fillable = [
        'user_id',
        'system_user_id', 
        'card_id'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'system_user_id' => 'integer',
        'card_id' => 'integer'
    ];

    /**
     * Relacionamento com User (admin)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com SystemUser
     */
    public function systemUser()
    {
        return $this->belongsTo(SystemUser::class);
    }

    /**
     * Relacionamento com Card
     */
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Scope para favoritos de usuário admin
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId)->whereNull('system_user_id');
    }

    /**
     * Scope para favoritos de usuário do sistema
     */
    public function scopeForSystemUser($query, $systemUserId)
    {
        return $query->where('system_user_id', $systemUserId)->whereNull('user_id');
    }

    /**
     * Verificar se um card é favorito para um usuário
     */
    public static function isFavorite($cardId, $userId = null, $systemUserId = null)
    {
        $query = self::where('card_id', $cardId);
        
        if ($userId) {
            $query->where('user_id', $userId)->whereNull('system_user_id');
        } elseif ($systemUserId) {
            $query->where('system_user_id', $systemUserId)->whereNull('user_id');
        } else {
            return false;
        }
        
        return $query->exists();
    }

    /**
     * Adicionar aos favoritos
     */
    public static function addToFavorites($cardId, $userId = null, $systemUserId = null)
    {
        if ($userId) {
            return self::firstOrCreate([
                'user_id' => $userId,
                'card_id' => $cardId,
                'system_user_id' => null
            ]);
        } elseif ($systemUserId) {
            return self::firstOrCreate([
                'system_user_id' => $systemUserId,
                'card_id' => $cardId,
                'user_id' => null
            ]);
        }
        
        return false;
    }

    /**
     * Remover dos favoritos
     */
    public static function removeFromFavorites($cardId, $userId = null, $systemUserId = null)
    {
        $query = self::where('card_id', $cardId);
        
        if ($userId) {
            $query->where('user_id', $userId)->whereNull('system_user_id');
        } elseif ($systemUserId) {
            $query->where('system_user_id', $systemUserId)->whereNull('user_id');
        } else {
            return false;
        }
        
        return $query->delete();
    }
}
