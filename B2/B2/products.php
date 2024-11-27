<?php
session_start();
include 'db_connection.php';

// جلب المنتجات
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
include 'db_connect.php';
?>



<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>قائمة المنتجات</title>
</head>
<body>
    <h1>المنتجات</h1>
    <div>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div style="border: 1px solid #ccc; padding: 10px; margin: 10px;">
                <h3><?php echo $row['name']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <p>السعر: <?php echo $row['price']; ?> جنيه</p>
                <form method="post" action="cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="add_to_cart">إضافة إلى العربة</button>
                </form>
            </div>
        <?php } ?>
    </div>
</body>
</html>
