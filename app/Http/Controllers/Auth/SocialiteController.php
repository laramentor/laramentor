<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     *
     * @param string $driver
     * @return RedirectResponse
     */
    public function redirect(string $driver): RedirectResponse
    {
        return Socialite::driver($driver)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param string $driver
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function callback(string $driver, Request $request): Response
    {
        $this->checkProvider($driver);

        try {
            $user = Socialite::driver($driver)->user();
            dd($user);
        } catch (\Exception $e) {
            dd($e);
        }

// - check if user exists - email/username(nickname)
// - if not create user
// - send email to user to confirm email address

//        $redirect = $request->input('redirect');
//        if ($redirect && Str::of($redirect)->startsWith($request->getSchemeAndHttpHost())) {
//            Redirect::setIntendedUrl($redirect);
//        }
//
//        if ($request->boolean('remember')) {
//            $request->session()->put('login.remember', true);
//        }
//
//        return Inertia::location(Socialite::driver($driver)->redirect());
    }

    /**
     * Check if the driver is activated.
     * @throws ValidationException
     */
    private function checkProvider(string $driver): void
    {
        if (! collect(config('auth.social.providers'))->contains($driver)) {
            throw ValidationException::withMessages([
                $driver => ['This provider does not exist.'],
            ]);
        }
    }
}
