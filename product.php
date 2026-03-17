<?php
require_once __DIR__ . '/vendor/autoload.php';
$config = require __DIR__ . '/config.php';

\Stripe\Stripe::setApiKey($config['secret_key']);

try {
    $products = \Stripe\Product::all([
        'limit' => 10,
        'active' => true,
        'expand' => ['data.default_price'] 
    ]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    die("Error fetching products: " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Laptop</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f9; }
        h1 { text-align: center; margin-bottom: 30px; }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .product-card { 
            background: white; 
            padding: 25px; 
            border-radius: 8px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
            display: flex;
            flex-direction: column;
            /* Since there's no image, we allow cards to be natural height */
        }
        
        .product-title { font-size: 20px; margin: 0 0 10px 0; color: #333; }
        
        .product-description { 
            font-size: 14px; 
            color: #666; 
            margin-bottom: 20px; 
            flex-grow: 1; /* Pushes the price and button to the bottom */
            line-height: 1.4;
        }

        .product-price { font-size: 22px; font-weight: bold; color: #333; margin-bottom: 15px; }
        
        .buy-button { 
            background-color: #635bff; 
            color: white; 
            padding: 12px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 16px; 
            width: 100%;
            font-weight: bold;
        }
        .buy-button:hover { background-color: #4b45c6; }
        .buy-button:disabled { background-color: #ccc; cursor: not-allowed; }
    </style>
</head>
<body>

    <h1>Best Gaming Laptop</h1>

    <div class="product-grid">
        <?php foreach ($products->data as $product): ?>
            <div class="product-card">
                
                <h2 class="product-title"><?= htmlspecialchars($product->name) ?></h2>
                
                <p class="product-description">
                    <?= $product->description ? htmlspecialchars($product->description) : '<em>No description available.</em>' ?>
                </p>

                <?php 
                $price = $product->default_price; 
                if ($price): 
                ?>
                    <div class="product-price">
                        <?= strtoupper($price->currency) ?> <?= number_format($price->unit_amount / 100, 2) ?>
                    </div>
                    
                    <button class="buy-button" data-price-id="<?= htmlspecialchars($price->id) ?>">
                        Buy Now
                    </button>
                <?php else: ?>
                    <div class="product-price" style="color: #888; font-size: 14px;">Price not set</div>
                    <button class="buy-button" disabled>Unavailable</button>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const buyButtons = document.querySelectorAll('.buy-button:not(:disabled)');
        
        buyButtons.forEach(button => {
            button.addEventListener('click', function() {
                const priceId = this.getAttribute('data-price-id');
                
                const originalText = this.textContent;
                this.disabled = true;
                this.textContent = 'Loading...';

                fetch('checkout.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ priceId: priceId })
                })
                .then(response => response.json())
                .then(session => {
                    if (session.error) {
                        alert('Error: ' + session.error);
                        this.disabled = false;
                        this.textContent = originalText;
                    } else if (session.url) {
                        window.location.href = session.url; 
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An unexpected error occurred.');
                    this.disabled = false;
                    this.textContent = originalText;
                });
            });
        });
    </script>
</body>
</html>