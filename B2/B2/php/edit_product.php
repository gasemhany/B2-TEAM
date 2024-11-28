<?php
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root"; // تأكد من تحديث اسم المستخدم وكلمة المرور
$password = ""; // إذا كانت هناك كلمة مرور، ضعها هنا
$dbname = "b2"; // اسم قاعدة البيانات الخاصة بك

$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// التحقق إذا كان هناك معرف للمنتج في الرابط
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // استعلام لجلب تفاصيل المنتج
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "Product ID is missing.";
    exit;
}

// معالجة التعديل
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // استعلام لتحديث تفاصيل المنتج
    $update_sql = "UPDATE products SET name='$name', description='$description', price='$price', category='$category' WHERE id=$product_id";

    if ($conn->query($update_sql) === TRUE) {
        echo "Product updated successfully.";
        header("Location: products.php"); // إعادة التوجيه بعد التحديث
        exit;
    } else {
        echo "Error: " . $update_sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="main-header">
        <div class="logo">B2 STORE</div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.html">Home</a></li>
                <li><a href="admin.html">Administrator</a></li>
            </ul>
        </nav>
    </header>

    <section id="edit-product">
        <h1>Edit Product</h1>
        <form method="POST" action="edit_product.php?id=<?php echo $product['id']; ?>">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required>

            <label for="description">Product Description:</label>
            <textarea id="description" name="description" required><?php echo $product['description']; ?></textarea>

            <label for="price">Product Price:</label>
            <input type="number" id="price" name="price" value="<?php echo $product['price']; ?>" required>

            <label for="category">Product Category:</label>
            <input type="text" id="category" name="category" value="<?php echo $product['category']; ?>" required>

            <button type="submit">Update Product</button>
        </form>
    </section>

    <footer class="main-footer">
        <p>&copy; 2024 B2 STORE | All Rights Reserved</p>
    </footer>
</body>
</html>
