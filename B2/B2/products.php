<?php
// تضمين الاتصال بقاعدة البيانات
include 'db_connect.php';

// استعلام لعرض جميع المنتجات
$stmt = $conn->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h1>قائمة المنتجات</h1>";
echo "<table border='1'>
        <tr>
            <th>الاسم</th>
            <th>الوصف</th>
            <th>السعر</th>
            <th>المخزون</th>
            <th>الصورة</th>
            <th>تعديل</th>
            <th>حذف</th>
        </tr>";

// عرض المنتجات في جدول
foreach ($products as $product) {
    echo "<tr>
            <td>" . htmlspecialchars($product['name']) . "</td>
            <td>" . htmlspecialchars($product['description']) . "</td>
            <td>" . $product['price'] . " د.إ</td>
            <td>" . $product['stock'] . "</td>
            <td><img src='" . $product['image_url'] . "' alt='" . $product['name'] . "' width='50'></td>
            <td><a href='edit_product.php?id=" . $product['id'] . "'>تعديل</a></td>
            <td><a href='delete_product.php?id=" . $product['id'] . "' onclick='return confirm(\"هل أنت متأكد من حذف هذا المنتج؟\");'>حذف</a></td>
        </tr>";
}

echo "</table>";

// إغلاق الاتصال بقاعدة البيانات
$conn = null;
?>
