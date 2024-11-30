<?php
// الاتصال بقاعدة البيانات
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

// جلب بيانات المنتج باستخدام المعرف (ID) من الرابط
$product_id = $_GET['id']; // فرضنا أن الـ ID يتم تمريره في الرابط عبر GET

// جلب المنتج من قاعدة البيانات
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "No product found!";
    exit();
}

// معالجة التعديل
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    // تحديث البيانات في قاعدة البيانات
    $update_sql = "UPDATE products SET product_name='$product_name', price='$price', description='$description', category='$category' WHERE id=$product_id";

    if ($conn->query($update_sql) === TRUE) {
        echo "Product updated successfully";
    } else {
        echo "Error updating product: " . $conn->error;
    }
}

$conn->close();
?>

<!-- نموذج تعديل المنتج -->
<form action="edit_product.php?id=<?php echo $product['id']; ?>" method="POST">
    <label for="product_name">Product Name:</label>
    <input type="text" id="product_name" name="product_name" value="<?php echo $product['product_name']; ?>" required><br><br>

    <label for="price">Price:</label>
    <input type="number" id="price" name="price" value="<?php echo $product['price']; ?>" required><br><br>

    <label for="description">Description:</label>
    <textarea id="description" name="description" required><?php echo $product['description']; ?></textarea><br><br>

    <label for="category">Category:</label>
    <input type="text" id="category" name="category" value="<?php echo $product['category']; ?>" required><br><br>

    <button type="submit">Update Product</button>
</form>
