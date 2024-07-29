<?php

namespace Database\Factories;

use App\Models\Quotation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuotationFactory extends Factory
{
    protected $model = Quotation::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'currency' => 'USD',
            'amount' => $this->faker->numberBetween(1000, 100000),
            'payment_method' => 'boleto',
            'converted_amount' => $this->faker->randomFloat(2, 100, 10000),
        ];
    }
}
