<?php
require_once __DIR__ . '/vendor/autoload.php';
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

MercadoPagoConfig::setAccessToken('APP_USR-3871915922687609-121614-8d2abda2b85d71e57eb8ed2d56aa5aec-3067578484');

$client = new PreferenceClient();
$preference = $client->create([
    "items" => [
        [
            "title" => "Teste",
            "quantity" => 1,
            "unit_price" => 10.0
        ]
    ]
]);

$link = $preference->sandbox_init_point ?? $preference->init_point;
var_dump($link);
