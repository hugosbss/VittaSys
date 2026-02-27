<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripePaymentService
{
    public function charge(float $amount, string $currency = 'brl')
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        return PaymentIntent::create([
            'amount' => $amount * 100,
            'currency' => $currency,
            'payment_method_types' => ['card'],
        ]);
    }
}