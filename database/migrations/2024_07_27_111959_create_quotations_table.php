<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('currency');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method');
            $table->decimal('converted_amount', 15, 2);
            $table->decimal('conversion_rate', 8, 2);
            $table->decimal('payment_fee', 8, 2);
            $table->decimal('conversion_fee', 8, 2);
            $table->decimal('net_amount', 8, 2);
            
            $table->timestamps();
    
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
