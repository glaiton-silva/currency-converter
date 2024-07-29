<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Models\Quotation;
use App\Mail\QuotationMail;
use App\Models\User;

class SendEmailTest extends TestCase
{
    use RefreshDatabase;

    public function testSendEmail()
    {
        Mail::fake();

        $user = User::factory()->create();
        $quotation = Quotation::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post('/quotations/email', [
            'quotation_id' => $quotation->id
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        Mail::assertSent(QuotationMail::class, function ($mail) use ($quotation) {
            return $mail->quotation->id === $quotation->id;
        });
    }
}
