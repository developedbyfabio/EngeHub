<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Tentar autenticar por username na tabela system_users
        $systemUser = \App\Models\SystemUser::where('username', $this->username)
            ->where('is_active', true)
            ->first();
            
        if ($systemUser && \Hash::check($this->password, $systemUser->password)) {
            // Criar uma sessão manual para o system user usando o guard correto
            \Auth::guard('system')->login($systemUser);
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // Se não funcionar, tentar autenticar por email na tabela users (para usuários admin)
        if (Auth::attempt(['email' => $this->username, 'password' => $this->password], $this->boolean('remember'))) {
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // Se não funcionar por email, tentar por username na tabela users
        if (Auth::attempt(['username' => $this->username, 'password' => $this->password], $this->boolean('remember'))) {
            RateLimiter::clear($this->throttleKey());
            return;
        }
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.failed'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('username')).'|'.$this->ip());
    }
} 