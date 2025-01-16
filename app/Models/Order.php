<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function debtors(): HasOne
    {
        return $this->HasOne(Debtor::class);
    }
}
