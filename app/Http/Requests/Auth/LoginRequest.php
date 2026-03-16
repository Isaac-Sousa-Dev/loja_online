<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

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
        
        $rules = [
            'password' => ['required', 'string']
        ];

        if((int) $this->query('first_login') != 1 || empty($this->query('first_login'))) {
            $rules['email'] = ['required', 'email'];
        }

        if((int) $this->query('first_login') === 1) {
            $rules['password'][] = 'confirmed';
            $rules['password_confirmation'] = ['required', 'string'];
        }

        return $rules;

    }

    public function messages()
    {
        return [
            'password.confirmed' => 'As senhas não conferem.',
            'password_confirmation.required' => 'Por favor, confirme sua senha.',
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
        
        // Detecta primeiro login via query string
        if ((int) $this->query('first_login') === 1) {

            if($this->verification_code === '' || $this->verification_code === null) {
                throw ValidationException::withMessages([
                    'verification_code' => 'Código de verificação inválido.',
                ]);
            }
            
            $user = \App\Models\User::where('verification_code', $this->verification_code)->first();
            if (!$user) {
                throw ValidationException::withMessages([
                    'verification_code' => 'Código de verificação inválido.',
                ]);
            }

            // Atualiza a senha e limpa o código de verificação (opcional)
            $user->password = Hash::make($this->password);
            $user->verification_code = null; // Opcional: evitar reutilização
            $user->save();

            Auth::login($user, $this->boolean('remember'));

            RateLimiter::clear($this->throttleKey());
            return;
        }

        // Fluxo normal de login
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
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
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
