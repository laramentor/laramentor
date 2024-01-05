<?php

namespace App\Models;

use App\Enums\SocialProvider;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Socialite\Contracts\User as SocialiteUser;

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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'provider' => SocialProvider::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFindUnique(
        Builder $query,
        string $provider,
        string $provider_id,
        string $email
    ): Builder {
        return $query
            ->where('email', $email)
            ->where('provider_id', $provider_id)
            ->where('provider', $provider);
    }
}
