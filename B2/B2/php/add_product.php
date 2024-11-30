<?php
// اتصال بقاعدة البيانات
$servername = "localhost";
$username = "root"; // اسم المستخدم لقاعدة البيانات
$password = "HDhHLkr360p7n8b5c!v1Zc9="; // كلمة المرور
$dbname = "s2709239_B2store"; // اسم قاعدة البيانات

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// معالجة البيانات المرسلة عبر POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    // إدخال البيانات إلى قاعدة البيانات
    $sql = "INSERT INTO products (product_name, price, description, category)
            VALUES ('$product_name', '$price', '$description', '$category')";

    if ($conn->query($sql) === TRUE) {
        echo "Product added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!-- نموذج إضافة المنتج -->
<form action="add_product.php" method="POST">
    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" required><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" required><br><br>

    <label for="description">Description:</label>
    <textarea id="description" name="description" required></textarea><br><br>

    <label for="category">Category:</label>
    <input type="text" id="category" name="category" required><br><br>

    <button type="submit">Add Product</button>
</form>
