<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Socialite\AbstractUser as SocialiteUser;

/**
 * @method static where(string $string, string $driver)
 */
class Socialite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeProvider(
        Builder $query,
        SocialiteUser $user,
        string $driver
    ): Builder {
        return $query
            ->where('provider_id', $user->getId())
            ->where('email', $user->getEmail())
            ->where('provider', $driver);
    }
}
