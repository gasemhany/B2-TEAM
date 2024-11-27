<?php
session_start();
include 'db_connection.php';

// تحقق من العربة في الجلسة
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// إضافة منتج إلى العربة
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];

    // جلب معلومات المنتج من قاعدة البيانات
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();

    if ($product) {
        // تحقق إذا كان المنتج موجودًا بالفعل في العربة
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }

        // إذا لم يكن موجودًا، أضفه للعربة
        if (!$found) {
            $product['quantity'] = 1;
            $_SESSION['cart'][] = $product;
        }
    }
}

// حذف منتج من العربة
if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    include 'db_connect.php';
}

// عرض محتويات العربة
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>عربة التسوق</title>
</head>
<body>
    <h1>عربة التسوق</h1>
    <div>
        <?php if (empty($_SESSION['cart'])) { ?>
            <p>العربة فارغة</p>
        <?php } else { ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>اسم المنتج</th>
                        <th>السعر</th>
                        <th>الكمية</th>
                        <th>المجموع</th>
                        <th>إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $item) { ?>
                        <tr>
                            <td><?php echo $item['name']; ?></td>
                            <td><?php echo $item['price']; ?> جنيه</td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo $item['price'] * $item['quantity']; ?> جنيه</td>
                            <td>
                                <form method="post" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" name="remove_from_cart">حذف</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <p><strong>الإجمالي الكلي:</strong> 
                <?php 
                    $total = 0;
                    foreach ($_SESSION['cart'] as $item) {
                        $total += $item['price'] * $item['quantity'];
                    }
                    echo $total . " ريال";
                ?>
            </p>
        <?php } ?>
    </div>
    <a href="products.php">العودة إلى المنتجات</a>
</body>
</html>
