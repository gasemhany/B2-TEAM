<?php
// اتصال بقاعدة البيانات
$conn = new mysqli('localhost', 'root', '', 's2719155_Bb2'); // استخدام قاعدة البيانات الجديدة s2719155_Bb2

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// معالجة بيانات النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // التحقق من المدخلات
    $product_name = trim($_POST['product_name']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $quantity = trim($_POST['quantity']);
    
    // التحقق من وجود القيم المدخلة
    if (empty($product_name) || empty($price) || empty($description) || empty($quantity)) {
        echo "<script>alert('Please fill in all fields.');</script>";
        exit();
    }

    // التحقق من صحة السعر والكمية
    if (!is_numeric($price) || !is_numeric($quantity)) {
        echo "<script>alert('Price and quantity must be numeric.');</script>";
        exit();
    }

    // التحقق من رفع صورة
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $image_name = $_FILES['product_image']['name'];
        $image_tmp_name = $_FILES['product_image']['tmp_name'];
        $upload_dir = "uploads/";

        // تحقق من نوع الملف
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($image_tmp_name);

        if (!in_array($file_type, $allowed_types)) {
            echo "<script>alert('Only JPG, PNG, and GIF files are allowed.');</script>";
            exit();
        }

        // تعيين اسم جديد للصورة
        $new_image_name = uniqid() . "_" . basename($image_name);
        $target_file = $upload_dir . $new_image_name;

        // رفع الصورة
        if (move_uploaded_file($image_tmp_name, $target_file)) {
            // إدخال المنتج في قاعدة البيانات باستخدام prepared statements
            $stmt = $conn->prepare("INSERT INTO products (name, price, description, quantity, image) 
                                    VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sdsss", $product_name, $price, $description, $quantity, $target_file);

            if ($stmt->execute()) {
                echo "<script>alert('Product added successfully!');</script>";
            } else {
                echo "<script>alert('Error adding product: " . $stmt->error . "');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Error uploading image.');</script>";
        }
    }
}

$conn->close();
?>
