<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'first_name',
        'last_name',
        'email',
        'phone_number',
    ];

    /**
     * Get the addresses from the customer.
     *
     * @return belongsToMany<Address, $this>
     */
    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(Address::class);
    }

    /**
     * Get the orders from the customer.
     *
     * @return hasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return hasMany<Debtor, $this>
     */
    public function debtors(): HasMany
    {
        return $this->hasMany(Debtor::class);
    }
}
