<?php
// اتصال بقاعدة البيانات
$conn = new mysqli('localhost', 'root', '', 's2719155_Bb2'); // تغيير اسم قاعدة البيانات إلى s2719155_Bb2

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// التحقق من وجود معرّف المنتج
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // جلب بيانات المنتج من قاعدة البيانات باستخدام prepared statement
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("Product not found.");
    }
} else {
    die("Invalid product ID.");
}

// معالجة بيانات النموذج عند الإرسال
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];

    // تحديث بيانات المنتج
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $image_name = $_FILES['product_image']['name'];
        $image_tmp_name = $_FILES['product_image']['tmp_name'];
        $upload_dir = "uploads/";
        $target_file = $upload_dir . basename($image_name);

        // رفع الصورة الجديدة
        if (move_uploaded_file($image_tmp_name, $target_file)) {
            $stmt = $conn->prepare("UPDATE products SET 
                                    name = ?, 
                                    price = ?, 
                                    description = ?, 
                                    quantity = ?, 
                                    image = ? 
                                    WHERE id = ?");
            $stmt->bind_param("sdsssi", $product_name, $price, $description, $quantity, $target_file, $product_id);
        } else {
            echo "<script>alert('Error uploading new image.');</script>";
            exit;
        }
    } else {
        $stmt = $conn->prepare("UPDATE products SET 
                                name = ?, 
                                price = ?, 
                                description = ?, 
                                quantity = ? 
                                WHERE id = ?");
        $stmt->bind_param("sdssi", $product_name, $price, $description, $quantity, $product_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully!');</script>";
        header("Location: admin_dashboard.php"); // إعادة توجيه بعد التحديث
        exit;
    } else {
        echo "<script>alert('Error updating product: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$conn->close();
?>
