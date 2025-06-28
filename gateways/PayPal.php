<?php
require_once 'GatewayInterface.php';

class PayPalGateway implements GatewayInterface
{
    public function pay(array $transaction): array
    {
        // Simulate PayPal logic
        return [
            'success' => true,
            'status' => 'paid',
            'reference_id' => uniqid('PAYPAL-'),
            'message' => 'Paid via PayPal'
        ];
    }
}
