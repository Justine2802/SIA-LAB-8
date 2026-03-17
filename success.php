<?php
// 1. Load Composer autoloader and Config
require_once __DIR__ . '/vendor/autoload.php';
$config = require __DIR__ . '/config.php';

// 2. Set your Stripe Secret Key
\Stripe\Stripe::setApiKey($config['secret_key']);

// 3. Grab the session_id from the URL that Stripe passed back
$sessionId = $_GET['session_id'] ?? null;

if (!$sessionId) {
    // If there is no session ID, someone just typed success.php into their browser directly
    die("Invalid request.");
}

try {
    // 4. Ask Stripe for the details of this specific checkout session
    $session = \Stripe\Checkout\Session::retrieve($sessionId);
    
    // You can also grab the customer's info if you want to personalize the page
    $customerName = $session->customer_details->name ?? 'Customer';
    $customerEmail = $session->customer_details->email ?? '';

} catch (\Exception $e) {
    die("Error fetching session details: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful!</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .success-card { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; max-width: 500px; }
        .checkmark { color: #32CD32; font-size: 50px; margin-bottom: 20px; }
        h1 { color: #333; margin-top: 0; }
        p { color: #666; font-size: 16px; line-height: 1.5; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #635bff; color: white; text-decoration: none; border-radius: 4px; }
        .btn:hover { background-color: #4b45c6; }
    </style>
</head>
<body>

    <div class="success-card">
        <div class="checkmark">✔</div>
        <h1>Payment Successful!</h1>
        <p>Thank you for your purchase, <strong><?= htmlspecialchars($customerName) ?></strong>!</p>
        
        <?php if ($customerEmail): ?>
            <p>A receipt has been sent to <?= htmlspecialchars($customerEmail) ?>.</p>
        <?php endif; ?>
        
        <p>Your order is currently being processed.</p>

        <a href="product.php" class="btn">Return to Store</a>
    </div>

</body>
</html>