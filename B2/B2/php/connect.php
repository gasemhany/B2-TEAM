<?php
// إعدادات الاتصال
$servername = "localhost"; // الخادم المحلي
$username = "root"; // اسم المستخدم (عادةً 'root' في XAMPP)
$password = "HDhHLkr360p7n8b5c!v1Zc9="; // كلمة المرور (عادةً فارغة في XAMPP)
$dbname = "s2709239_B2store"; // اسم قاعدة البيانات التي أنشأتها في phpMyAdmin

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully"; // هذا الرسالة تظهر إذا تم الاتصال بنجاح
}
?>
