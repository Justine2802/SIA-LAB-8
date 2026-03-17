<?php
require_once __DIR__ . '/vendor/autoload.php';
$config = require __DIR__ . '/config.php';

\Stripe\Stripe::setApiKey($config['secret_key']);

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['priceId'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No price ID provided']);
    exit;
}

$YOUR_DOMAIN = 'http://localhost/stripe-php-app';

try {
    $session = \Stripe\Checkout\Session::create([
        'line_items' => [[
            'price' => $data['priceId'], 
            'quantity' => 1,             
        ]],
        'mode' => 'subscription', 
        
        'success_url' => $YOUR_DOMAIN . '/success.php?session_id={CHECKOUT_SESSION_ID}',
        
        'cancel_url' => $YOUR_DOMAIN . '/cancel.php',
    ]);

    header('Content-Type: application/json');
    echo json_encode(['url' => $session->url]);

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}