<?php
require 'db_connect.php'; // استدعاء ملف الاتصال بقاعدة البيانات

try {
    // استعلام لجلب المنتجات من جدول "products"
    $stmt = $conn->prepare("SELECT id, name, price, description, image FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - B2 Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">B2 Store</div>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="products.php" class="active">Products</a></li>
                <li><a href="payment.html">Payment</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="products">
            <h1>Our Products</h1>
            <div class="product-list">
                <?php foreach ($products as $product): ?>
                    <div class="product-item">
                        <img src="images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <h2><?= htmlspecialchars($product['name']) ?></h2>
                        <p><?= htmlspecialchars($product['description']) ?></p>
                        <p><strong>Price: $<?= htmlspecialchars($product['price']) ?></strong></p>
                        <button>Add to Cart</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 B2 Store. All rights reserved.</p>
    </footer>
</body>
</html>
