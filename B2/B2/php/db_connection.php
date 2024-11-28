<?php
$servername = "localhost";
$username = "root";
$password = "";  // أو أدخل كلمة مرور MySQL هنا إذا كانت موجودة
$dbname = "b2";  // اسم قاعدة البيانات الخاصة بك

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
