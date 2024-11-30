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

// جلب الـ ID من الرابط
$product_id = $_GET['id']; // فرضنا أن الـ ID يتم تمريره في الرابط عبر GET

// تنفيذ عملية الحذف
$delete_sql = "DELETE FROM products WHERE id=$product_id";

if ($conn->query($delete_sql) === TRUE) {
    echo "Product deleted successfully";
} else {
    echo "Error deleting product: " . $conn->error;
}

$conn->close();
?>
