<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('conversion_fees', function (Blueprint $table) {
            $table->id();
            $table->decimal('payment_fee_boleto', 5, 4)->default(0.0145);
            $table->decimal('payment_fee_credit_card', 5, 4)->default(0.0763);
            $table->decimal('conversion_fee_below_3000', 5, 4)->default(0.02);
            $table->decimal('conversion_fee_above_3000', 5, 4)->default(0.01);
            $table->timestamps();
        });

        // Insere o primeiro registro com valores padrão
        DB::table('conversion_fees')->insert([
            'payment_fee_boleto' => 0.0145,
            'payment_fee_credit_card' => 0.0763,
            'conversion_fee_below_3000' => 0.02,
            'conversion_fee_above_3000' => 0.01,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversion_fees');
    }
};
