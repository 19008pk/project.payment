<?php

require_once 'GatewayInterface.php';

class Gateway implements GatewayInterface
{
    public function pay(array $transaction): array
    {
        // Simulate successful pay logic
        return [
            'success' => true,
            'status' => 'paid',
            'reference_id' => uniqid('pay-ref'),
            'message' => 'Successfully paid the transaction'
        ];
    }
    public function refund(array $transaction): array
    {
        // Simulate successful refund logic
        return [
            'success' => true,
            'status' => 'refunded',
            'reference_id' => uniqid('refund-ref'),
            'message' => 'Successfully refunded the transaction'
        ];
    }
}
