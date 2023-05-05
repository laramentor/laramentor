<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
    ];

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
}
