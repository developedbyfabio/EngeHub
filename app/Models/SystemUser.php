<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SystemUser extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'username',
        'password',
        'notes',
        'is_active',
        'user_id',
        'secret_url',
        'secret_url_expires_at',
        'secret_url_generated_at',
        'secret_url_enabled'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'secret_url_enabled' => 'boolean',
        'secret_url_expires_at' => 'datetime',
        'secret_url_generated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cards()
    {
        return $this->belongsToMany(Card::class, 'system_user_cards')
                    ->withTimestamps();
    }

    /**
     * Verifica se o usuário pode visualizar um sistema específico
     */
    public function canViewSystem($cardId)
    {
        return $this->cards()->where('cards.id', $cardId)->exists();
    }

    /**
     * Verifica se o usuário pode visualizar todos os sistemas
     */
    public function canViewAllSystems()
    {
        $totalCards = Card::count();
        $userCards = $this->cards()->count();
        
        return $totalCards > 0 && $userCards === $totalCards;
    }

    /**
     * Hash da senha automaticamente
     */
    public function setPasswordAttribute($value)
    {
        // Só fazer hash se a senha não for 'N/A' (usado para usuários vinculados)
        if ($value === 'N/A') {
            $this->attributes['password'] = $value;
        } else {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     * Scope para usuários ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Verifica se o usuário pode ver senhas (sempre true para SystemUser)
     */
    public function canViewPasswords()
    {
        return true;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getAttribute($this->getAuthIdentifierName());
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string|null
     */
    public function getRememberToken()
    {
        return $this->remember_token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Relacionamento com permissões de logins
     */
    public function loginPermissions()
    {
        return $this->hasMany(SystemLoginPermission::class);
    }

    /**
     * Relacionamento many-to-many com SystemLogin através de permissões
     */
    public function systemLogins()
    {
        return $this->belongsToMany(SystemLogin::class, 'system_login_permissions')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    /**
     * Obtém logins que o usuário pode visualizar
     */
    public function getAccessibleLogins()
    {
        return $this->systemLogins()
                    ->wherePivot('is_active', true)
                    ->get();
    }

    /**
     * Verifica se o usuário pode visualizar um login específico
     */
    public function canViewLogin($systemLoginId)
    {
        return $this->loginPermissions()
                    ->where('system_login_id', $systemLoginId)
                    ->where('is_active', true)
                    ->exists();
    }

    /**
     * Relacionamento com favoritos
     */
    public function favorites()
    {
        return $this->hasMany(UserFavorite::class);
    }

    /**
     * Relacionamento many-to-many com cards favoritos
     */
    public function favoriteCards()
    {
        return $this->belongsToMany(Card::class, 'user_favorites', 'system_user_id', 'card_id')
                    ->withTimestamps();
    }

    /**
     * Verifica se um card é favorito
     */
    public function isFavoriteCard($cardId)
    {
        return UserFavorite::isFavorite($cardId, null, $this->id);
    }

    /**
     * Adiciona card aos favoritos
     */
    public function addToFavorites($cardId)
    {
        return UserFavorite::addToFavorites($cardId, null, $this->id);
    }

    /**
     * Remove card dos favoritos
     */
    public function removeFromFavorites($cardId)
    {
        return UserFavorite::removeFromFavorites($cardId, null, $this->id);
    }

    /**
     * Relacionamento com logs de acesso por URL secreta
     */
    public function secretUrlAccessLogs()
    {
        return $this->hasMany(SecretUrlAccessLog::class);
    }

    /**
     * Gera uma nova URL secreta (aleatória)
     */
    public function generateSecretUrl(): string
    {
        $secretUrl = Str::random(32);
        
        $this->update([
            'secret_url' => $secretUrl,
            'secret_url_generated_at' => now(),
            'secret_url_enabled' => true
        ]);
        
        return $secretUrl;
    }

    /**
     * Define uma URL secreta personalizada
     */
    public function setCustomSecretUrl(string $slug): string
    {
        // Limpar e formatar o slug
        $slug = Str::slug($slug);
        
        $this->update([
            'secret_url' => $slug,
            'secret_url_generated_at' => now(),
            'secret_url_enabled' => true
        ]);
        
        return $slug;
    }

    /**
     * Verifica se a URL secreta está válida
     */
    public function isSecretUrlValid(): bool
    {
        // Verificar se está habilitada
        if (!$this->secret_url_enabled) {
            return false;
        }
        
        // Verificar se existe
        if (!$this->secret_url) {
            return false;
        }
        
        // Verificar se não expirou
        if ($this->secret_url_expires_at && $this->secret_url_expires_at->isPast()) {
            return false;
        }
        
        return true;
    }

    /**
     * Regenera a URL secreta
     */
    public function regenerateSecretUrl(): string
    {
        return $this->generateSecretUrl();
    }

    /**
     * Desabilita a URL secreta
     */
    public function disableSecretUrl(): void
    {
        $this->update(['secret_url_enabled' => false]);
    }

    /**
     * Habilita a URL secreta
     */
    public function enableSecretUrl(): void
    {
        // Se não tem URL, gera uma
        if (!$this->secret_url) {
            $this->generateSecretUrl();
        } else {
            $this->update(['secret_url_enabled' => true]);
        }
    }

    /**
     * Obtém a URL completa da URL secreta
     */
    public function getSecretUrlAttribute($value)
    {
        return $value;
    }

    /**
     * Obtém a URL completa para acesso
     */
    public function getFullSecretUrlAttribute(): ?string
    {
        if (!$this->secret_url) {
            return null;
        }
        
        return url('/s/' . $this->secret_url);
    }
}
