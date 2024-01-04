<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Class AuthService
 */
class AuthService
{
    public function __construct(
        private readonly AuthFactory $auth,
    ) {
    }

    protected int $maxAttempts = 5;

    /**
     * Auth validation rules for login
     */
    public static function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ];
    }

    /**
     * Perform authentication
     *
     * @throws ValidationException
     */
    public function authenticate(
        string $email,
        string $password,
        bool $remember = false,
        string $guard = 'web'
    ): Authenticatable {

        $this->ensureIsNotRateLimited($email);

        $credentials = [
            'email' => $email,
            'password' => $password,
        ];

        if (! $this->auth->guard($guard)->attempt($credentials, $remember)) {
            RateLimiter::hit($this->throttleKey($email));

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey($email));

        return Auth::user();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(string $email): void
    {
        $rateLimited = RateLimiter::tooManyAttempts(
            $this->throttleKey($email),
            $this->maxAttempts
        );

        if (! $rateLimited) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn(
            $this->throttleKey($email)
        );

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
    public function throttleKey(string $email): string
    {
        return Str::transliterate(
            Str::lower($email) . '|' . Request::ip()
        );
    }

    /**
     * Login with a user instances
     */
    public function loginUser(
        User $user,
        bool $remember = false,
        string $guard = 'web'
    ): User {
        event(new Login($guard, $user, $remember));
        $this->auth->guard($guard)->login($user);

        return $user;
    }

    /**
     * Logout an authenticated user
     */
    public function logout(string $guard = 'web'): bool
    {
        if (! $this->auth->guard($guard)->check()) {
            return false;
        }

        $this->auth->guard($guard)->logout();

        session()->invalidate();
        session()->regenerateToken();

        return true;
    }

    /**
     * Perform login authentication
     *
     * @throws ValidationException
     */
    public function emailLogin(
        string $email,
        string $password,
        bool $remember = false
    ): Authenticatable {

        $user = $this->authenticate($email, $password, $remember);
        session()->regenerate();

        return $user;
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(string $guard = 'web'): bool
    {
        $this->auth->guard($guard)->logout();

        session()->invalidate();
        session()->regenerateToken();

        return true;
    }
}
