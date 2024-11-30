<?php
// إعدادات الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "HDhHLkr360p7n8b5c!v1Zc9=";
$dbname = "s2709239_B2store";

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// تعيين الترميز إلى UTF-8
$conn->set_charset("utf8");

// معالجة البيانات المرسلة عبر POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // استقبال البيانات من النموذج
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    // استخدام الاستعلامات المحضرة (Prepared Statements)
    $stmt = $conn->prepare("INSERT INTO products (product_name, price, description, category) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $product_name, $price, $description, $category);

    // تنفيذ الاستعلام والتحقق من النتيجة
    if ($stmt->execute()) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // إغلاق الاستعلام
    $stmt->close();
}

// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>

<!-- نموذج إضافة المنتج -->
<form action="add_product.php" method="POST">
    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" required><br><br>

    <label for="description">Description:</label>
    <textarea id="description" name="description" required></textarea><br><br>

    <label for="category">Category:</label>
    <input type="text" id="category" name="category" required><br><br>

    <button type="submit">Add Product</button>
</form>
