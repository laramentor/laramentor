<?php

namespace App\Traits;
 
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
 
trait DateTimezone
{
    /**
     * Interact with the created at
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($date) => Carbon::parse($date)->timezone(config('app.timezone'))
        );
    }

    /**
     * Interact with the updated at
     */
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($date) => Carbon::parse($date)->timezone(config('app.timezone'))
        );
    }
}