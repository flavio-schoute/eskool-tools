<?php

namespace App\Models;

use App\Enums\Debtor\DebtorStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Debtor extends Model
{
    /** @use HasFactory<\Database\Factories\DebtorFactory> */
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => DebtorStatus::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
