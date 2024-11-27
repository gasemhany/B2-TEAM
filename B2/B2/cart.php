<?php
session_start();
$cart = $_SESSION['cart'] ?? [];
$totalPrice = 0;

foreach ($cart as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - B2 Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">B2 Store</div>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="cart.php" class="active">Cart</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="cart">
            <h1>Your Cart</h1>
            <?php if (!empty($cart)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td>$<?= number_format($item['price'], 2) ?></td>
                                <td><?= htmlspecialchars($item['quantity']) ?></td>
                                <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
