<?php

class TransactionService
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function updateTransactionStatus($id, $response, $gateway = null)
    {
        if ($gateway !== null) {
            $stmt = $this->pdo->prepare("UPDATE transactions SET status = ?, payment_gateway = ? WHERE id = ?");
            return $stmt->execute([$response['status'], $gateway, $id]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE transactions SET status = ? WHERE id = ?");
            return $stmt->execute([$response['status'], $id]);
        }
    }
}
