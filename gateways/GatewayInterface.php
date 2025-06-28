<?php

interface GatewayInterface
{
    public function pay(array $transaction): array;
    public function refund(array $transaction): array;
}
