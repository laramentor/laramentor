<?php

namespace App\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\{
    Lockout,
    Login
};

use Illuminate\Support\Facades\{
    Request,
    Auth,
    RateLimiter
};

use App\Models\User;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

/**
 * Class AuthService
 * @package App\Services
 */
class AuthService
{
    public function __construct(
        private readonly AuthFactory $auth,
    ) {
    }

    /**
     * @var int
     */
    protected int $maxAttempts = 5;

    /**
     * Auth validation rules for login
     */
    public static function rules() : array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string',
            'remember' => 'nullable|boolean'
        ];
    }

    /**
     * Perform authentication
     *
     * @param string $email
     * @param string $password
     * @param bool $remember
     * @param string $guard
     * @return Authenticatable
     * @throws ValidationException
     */
    public function authenticate(
        string $email,
        string $password,
        bool $remember=false,
        string $guard='web'
    ) : Authenticatable {

        $this->ensureIsNotRateLimited($email);

        $credentials = [
            'email'    => $email,
            'password' => $password,
        ];

        if (! $this->auth->guard($guard)->attempt($credentials, $remember))
        {
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
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(string $email) : void
    {
        $rateLimited = RateLimiter::tooManyAttempts(
            $this->throttleKey($email),
            $this->maxAttempts
        );

        if (!$rateLimited) {
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
     * @param string $email
     * @return string
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
        bool $remember=false,
        string $guard='web'
    ): User {
        event(new Login($guard, $user, $remember));
        $this->auth->guard($guard)->login($user);
        return $user;
    }

    /**
     * Logout an authenticated user
     *
     * @param string $guard
     * @return bool
     */
    public function logout(string $guard='web') : bool
    {
        if(!$this->auth->guard($guard)->check()) {
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
     * @param string $email
     * @param string $password
     * @param bool $remember
     * @return Authenticatable
     * @throws ValidationException
     */
    public function emailLogin(
        string $email,
        string $password,
        bool $remember=false
    ) : Authenticatable {

        $user = $this->authenticate($email, $password, $remember);
        session()->regenerate();

        return $user;
    }

    /**
     * Destroy an authenticated session.
     *
     * @param string $guard
     * @return bool
     */
    public function destroy(string $guard='web') : bool
    {
        $this->auth->guard($guard)->logout();

        session()->invalidate();
        session()->regenerateToken();

        return true;
    }
}
