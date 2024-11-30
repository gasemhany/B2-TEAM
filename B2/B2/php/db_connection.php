<?php
$servername = "localhost";
$username = "root";
$password = "HDhHLkr360p7n8b5c!v1Zc9=";  // أو أدخل كلمة مرور MySQL هنا إذا كانت موجودة
$dbname = "s2709239_B2store";  // اسم قاعدة البيانات الخاصة بك

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
