<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $fillable = [
        'full_name',
        'first_name',
        'last_name',
        'email',
        'phone_number'
    ];

    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(Address::class);
    }
}
