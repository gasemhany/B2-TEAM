<?php
// إعدادات الاتصال
$servername = "23.88.73.88"; // الخادم المحلي
$username = "u2719155_XZObLAYBtO"; // اسم المستخدم (عادةً 'root' في XAMPP)
$password = "z4f!J@wYWsPHW.L1z9yZF6WI"; // كلمة المرور (عادةً فارغة في XAMPP)
$dbname = "s2719155_Bb2"; // اسم قاعدة البيانات التي أنشأتها في phpMyAdmin

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully"; // هذا الرسالة تظهر إذا تم الاتصال بنجاح
}
?>
