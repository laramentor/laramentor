<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirect(string $driver): RedirectResponse
    {
        $this->checkProvider($driver);

        return Socialite::driver($driver)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @throws ValidationException
     */
    public function callback(string $driver, Request $request): Response
    {
        $this->checkProvider($driver);

        try {
            $socialite = Socialite::driver($driver)->user();

            $user = User::query()
                ->with(['socialite' => fn ($query) => $query->provider($socialite, $driver)])
                ->where('email', $socialite->getEmail())
                ->first();
        } catch (Exception $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Unable to login with ' . $driver . '.',
            ]);
        }

        if (! $user || ! $user->socialite) {
            session(['socialite' => $this->socialiteToArray($socialite, $driver)]);

            return Redirect::route('register');
        }

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Check if the driver is activated.
     *
     * @throws ValidationException
     */
    private function checkProvider(string $driver): void
    {
        if (! collect(config('auth.social.providers'))->contains($driver)) {
            throw ValidationException::withMessages([
                'error' => ['This provider does not exist.'],
            ]);
        }
    }

    private function socialiteToArray(
        SocialiteUser $user,
        string $provider
    ): array {
        return [
            'provider_id' => $user->getId(),
            'provider' => $provider,
            'nickname' => $user->getNickname(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'avatar' => $user->getAvatar(),
        ];
    }
}
