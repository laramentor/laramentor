<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static updateOrCreate(array $array, array $array1)
 * @method static findSocialite(string $driver, string $getId, string|null $getEmail)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'is_mentor',
        'is_mentee',
        'avatar',
    ];

    public function getRouteKeyName(): string
    {
        return 'username';
    }

    public function mentor(): HasOne
    {
        return $this->hasOne(Mentor::class);
    }

    public function mentee(): HasOne
    {
        return $this->hasOne(Mentee::class);
    }

    public function scopeMentors($query)
    {
        return $query->whereHas('mentor');
    }

    public function scopeMentees($query)
    {
        return $query->whereHas('mentee');
    }

    public function getIsMentorAttribute()
    {
        return $this->mentor()->exists();
    }

    public function getIsMenteeAttribute()
    {
        return $this->mentee()->exists();
    }

    /**
     * Get the URL to the user's profile photo.
     */
    public function getAvatarAttribute()
    {
        return $this->avatar_url ? $this->avatar_url : $this->defaultAvatarUrl();
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     */
    protected function defaultAvatarUrl(): string
    {
        $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=4f46e5&background=c7d2fe';
    }

    public function socialite(): HasOne
    {
        return $this->hasOne(Socialite::class);
    }

    public function scopeFindSocialite(
        Builder $query,
        string $provider,
        string $provider_id,
        string $email
    ): Builder {
        return $query
            ->whereHas(
                'socialite',
                fn ($query) => $query->findUnique($provider, $provider_id, $email)
            )
            ->where('email', $email);
    }
}
