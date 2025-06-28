<?php

interface GatewayInterface
{
    public function pay(array $transaction): array;
}
