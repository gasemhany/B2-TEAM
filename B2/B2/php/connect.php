<?php
// إعدادات الاتصال
$servername = "Server: sql310.infinityfree.com"; // الخادم المحلي
$username = "u2719155_XZObLAYBtO"; // اسم المستخدم (عادةً 'root' في XAMPP)
$password = ""; // كلمة المرور (عادةً فارغة في XAMPP)
$dbname = "if0_37886076_b2"; // اسم قاعدة البيانات التي أنشأتها في phpMyAdmin

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully"; // هذا الرسالة تظهر إذا تم الاتصال بنجاح
}
?>
