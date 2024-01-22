<?php

namespace App\Listeners;

use App\Events\SocialiteRegistered;

class UpdateAvatarUrl
{
    /**
     * Handle the event.
     */
    public function handle(SocialiteRegistered $event): void
    {
        if (! $event->socialite->avatar) {
           return;
        }

        $event->user->update([
            'avatar_url' => $event->socialite->avatar,
        ]);
    }
}
