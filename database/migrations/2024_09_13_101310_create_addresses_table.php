<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            $table->string('address_line');

            $table->string('street');

            $table->integer('house_number');
            $table->char('house_number_addition')->nullable();

            $table->string('zipcode');

            $table->string('city');
            
            $table->string('country');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
