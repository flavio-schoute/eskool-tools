<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table): void {
            $table->id();

            $table->string('address_line');

            $table->string('street');
            $table->string('house_number');

            $table->string('postal_code');
            $table->string('city');

            $table->string('country');

            $table->unique(['street', 'house_number', 'postal_code', 'city'], 'unique_address');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
