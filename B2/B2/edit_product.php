<?php
// تضمين الاتصال بقاعدة البيانات
include 'db_connect.php';

// التحقق من وجود معرّف المنتج في الرابط
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // استعلام لجلب بيانات المنتج حسب المعرف
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $product_id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // عرض النموذج مع القيم الحالية
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // تحديث بيانات المنتج
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            $image_url = $_POST['image_url'];

            // استعلام لتحديث المنتج
            $update_stmt = $conn->prepare("UPDATE products SET name = :name, description = :description, price = :price, stock = :stock, image_url = :image_url WHERE id = :id");
            $update_stmt->bindParam(':id', $product_id);
            $update_stmt->bindParam(':name', $name);
            $update_stmt->bindParam(':description', $description);
            $update_stmt->bindParam(':price', $price);
            $update_stmt->bindParam(':stock', $stock);
            $update_stmt->bindParam(':image_url', $image_url);

            if ($update_stmt->execute()) {
                echo "<p>تم تعديل المنتج بنجاح!</p>";
            } else {
                echo "<p>حدث خطأ أثناء تعديل المنتج.</p>";
            }
        }
?>
<h1>تعديل المنتج</h1>
<form action="edit_product.php?id=<?php echo $product['id']; ?>" method="POST">
    <label for="name">الاسم:</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br><br>

    <label for="description">الوصف:</label>
    <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea><br><br>

    <label for="price">السعر:</label>
    <input type="number" name="price" value="<?php echo $product['price']; ?>" required><br><br>

    <label for="stock">المخزون:</label>
    <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required><br><br>

    <label for="image_url">رابط الصورة:</label>
    <input type="text" name="image_url" value="<?php echo $product['image_url']; ?>" required><br><br>

    <button type="submit">تحديث المنتج</button>
</form>

<?php
    } else {
        echo "<p>المنتج غير موجود.</p>";
    }
} else {
    echo "<p>معرف المنتج مفقود.</p>";
}

// إغلاق الاتصال بقاعدة البيانات
$conn = null;
?>
