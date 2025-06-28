<?php
require_once 'GatewayInterface.php';

class Razorpay implements GatewayInterface
{
    public function pay(array $transaction): array
    {
        // Simulate Razorpay logic
        return [
            'success' => true,
            'status' => 'paid',
            'reference_id' => uniqid('Razorpay-'),
            'message' => 'Paid via Razorpay'
        ];
    }
}
