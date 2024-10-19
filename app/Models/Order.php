<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'plug_and_play_order_id',
        'invoice_number',
        'invoice_date',
        'full_name',
        'products',
        'price',
        'price_with_tax',
    ];
}
