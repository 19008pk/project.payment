<?php

require_once __DIR__ . '/../gateways/GatewayInterface.php';

class PaymentService
{
    public static function process($gateway, $transaction): array
    {
        try {
            $gatewayInstance = self::resolveGatewayInstance($gateway);

            return $gatewayInstance->pay($transaction);
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'status' => 'failed',
                'reference_id' => null,
                'message' => 'Unexpected error: ' . $e->getMessage()
            ];
        }
    }

    private static function resolveGatewayInstance(string $gateway): GatewayInterface
    {
        $class = self::getGatewayClass($gateway);

        if (!$class || !file_exists($class)) {
            throw new \Exception("Gateway file for [$gateway] not found at path: $class");
        }

        require_once $class;

        $className = self::getGatewayClassName($gateway);

        if (!class_exists($className)) {
            throw new \Exception("Payment class [$className] not found");
        }

        $instance = new $className();

        if (!($instance instanceof GatewayInterface)) {
            throw new \Exception("[$className] does not implement GatewayInterface");
        }

        return $instance;
    }

    private static function getGatewayMap(): array
    {
        static $map;
        if (!$map) {
            $map = require __DIR__ . '/../config/gateways.php';
        }
        return $map;
    }

    private static function getGatewayClass(string $gateway): ?string
    {
        $map = self::getGatewayMap();
        return isset($map[$gateway]) ? __DIR__ . '/../gateways/' . $map[$gateway] . '.php' : null;
    }

    private static function getGatewayClassName(string $gateway): string
    {
        $map = self::getGatewayMap();
        return $map[$gateway] ?? '';
    }
}
