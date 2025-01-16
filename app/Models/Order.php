<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'plug_and_play_order_id',
        'invoice_number',
        'invoice_date',
        'invoice_status',
        'full_name',
        'products',
        'amount',
        'amount_with_tax',
        'tax_amount',
        'contact_person',
        'customer_id',
        'billing_address_id',
    ];

    /**
     * Get the customer associated with the order.
     *
     * @return BelongsTo<Customer, covariant $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the customers billing address associated with the order.
     *
     * @return BelongsTo<Address, covariant $this>
     */
    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    /**
     * Get the debtor associated with the order.
     *
     * @return HasOne<Debtor, covariant $this>
     */
    public function debtors(): HasOne
    {
        return $this->HasOne(Debtor::class);
    }
}
