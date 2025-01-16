<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();

            $table->string('plug_and_play_order_id')->unique();
            $table->string('invoice_number')->unique();

            $table->dateTime('invoice_date');

            // Todo: Velden afdwingen
            $table->string('invoice_status');

            $table->string('full_name');

            $table->text('products');

            $table->decimal('amount');
            $table->decimal('amount_with_tax');
            $table->decimal('tax_amount');

            $table->string('contact_person')->nullable();

            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('billing_address_id')->constrained('addresses')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
