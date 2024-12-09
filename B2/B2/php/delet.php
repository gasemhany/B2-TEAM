<?php
// الاتصال بقاعدة البيانات
$servername = "23.88.73.88";
$username = "u2719155_XZObLAYBtO"; // اسم المستخدم لقاعدة البيانات
$password = "z4f!J@wYWsPHW.L1z9yZF6WI"; // كلمة المرور
$dbname = "s2719155_Bb2"; // اسم قاعدة البيانات

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// التحقق من وجود الـ id في الرابط
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // استخدام prepared statement لحذف المنتج
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id); // ربط المعامل مع الـ id (النوع i للـ integer)

    if ($stmt->execute()) {
        echo "Product deleted successfully";
    } else {
        echo "Error deleting product: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid product ID.";
}

$conn->close();
?>
