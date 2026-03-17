<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Cancelled</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .cancel-card { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; max-width: 500px; }
        .icon { color: #ff6b6b; font-size: 50px; margin-bottom: 20px; }
        h1 { color: #333; margin-top: 0; }
        p { color: #666; font-size: 16px; line-height: 1.5; margin-bottom: 10px; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #635bff; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; }
        .btn:hover { background-color: #4b45c6; }
    </style>
</head>
<body>

    <div class="cancel-card">
        <div class="icon">✖</div>
        <h1>Checkout Cancelled</h1>
        <p>Your order has been cancelled, and you have not been charged.</p>
        <p>If you changed your mind or experienced an issue, you can always try again.</p>
        
        <a href="product.php" class="btn">Return to Store</a>
    </div>

</body>
</html>