<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    protected $fillable = [
        'address_line',
        'street',
        'house_number',
        'house_number_addition',
        'zipcode',
        'city',
        'country'
    ];

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class);
    }
}
