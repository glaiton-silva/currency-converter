<?php

namespace Tests\Unit;

use Tests\TestCase;
use ReflectionMethod;
use App\Http\Controllers\QuotationController;

class QuotationTest extends TestCase
{
    public function testConversionRate()
    {
        $controller = new QuotationController();
        $method = new ReflectionMethod(QuotationController::class, 'getConversionRate');
        $method->setAccessible(true);

        $currency = 'USD';
        $rate = $method->invoke($controller, $currency);
        
        $this->assertIsNumeric($rate);
        $this->assertGreaterThan(0, $rate);
    }

    public function testCalculateConversion()
    {
        $controller = new QuotationController();
        $method = new ReflectionMethod(QuotationController::class, 'calculateConversion');
        $method->setAccessible(true);

        $amount = 5000;
        $paymentMethod = 'boleto';
        $conversionRate = 5.0;

        $convertedAmount = $method->invoke($controller, $amount, $paymentMethod, $conversionRate);
        
        $this->assertIsNumeric($convertedAmount);
        $this->assertGreaterThan(0, $convertedAmount);
    }

    public function testCalculateFees()
    {
        $controller = new QuotationController();
        $method = new ReflectionMethod(QuotationController::class, 'calculateFees');
        $method->setAccessible(true);

        $amount = 5000;
        $paymentMethod = 'boleto';

        $fees = $method->invoke($controller, $amount, $paymentMethod);

        $this->assertArrayHasKey('paymentFee', $fees);
        $this->assertArrayHasKey('conversionFee', $fees);
        $this->assertGreaterThan(0, $fees['paymentFee']);
        $this->assertGreaterThan(0, $fees['conversionFee']);
    }
}
