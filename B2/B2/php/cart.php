<?php
session_start();

// افتراضياً، إذا لم تكن هناك سلة، نقوم بإنشاء واحدة فارغة
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// إضافة منتج إلى السلة
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_price = $_POST['product_price'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];

    // إذا كان المنتج موجودًا في السلة بالفعل، نقوم بتحديث الكمية
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // إضافة المنتج الجديد إلى السلة
        $_SESSION['cart'][$product_id] = [
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => $quantity
        ];
    }
}

// حذف منتج من السلة
if (isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
}

// تحديث الكميات
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if ($quantity == 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        }
    }
}

// حساب الإجمالي
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>
    <header>
        <div class="logo">B2 STORE</div>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="cart.html" class="active">Cart</a></li>
            </ul>
        </nav>
    </header>

    <section class="cart-section">
        <h1>Your Shopping Cart</h1>
        <div class="cart-container">
            <div class="cart-item">
                <img src="IMG/product1.jpg" alt="Product 1">
                <div class="item-details">
                    <h2>Product 1</h2>
                    <p>Price: $20</p>
                    <p>Quantity:</p>
                    <input type="number" value="1" min="1">
                </div>
                <button class="remove-btn">Remove</button>
            </div>

            <div class="cart-item">
                <img src="IMG/product2.jpg" alt="Product 2">
                <div class="item-details">
                    <h2>Product 2</h2>
                    <p>Price: $35</p>
                    <p>Quantity:</p>
                    <input type="number" value="1" min="1">
                </div>
                <button class="remove-btn">Remove</button>
            </div>

            <div class="cart-summary">
                <h2>Summary</h2>
                <p>Subtotal: 55</p>
                <p>Tax: 5</p>
                <p><strong>Total: 60</strong></p>
                <button class="checkout-btn">Checkout</button>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 B2 Store. All Rights Reserved.</p>
    </footer>

    <script src="cart.js"></script>
    
</body>
</html>
