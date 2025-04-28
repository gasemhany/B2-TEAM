<?php
// إعدادات الاتصال بقاعدة البيانات
$servername = "localhost"; // عنوان الخادم (يمكن تغييره إذا كان localhost)
$username = "root"; // اسم المستخدم لقاعدة البيانات
$password = ""; // كلمة المرور لقاعدة البيانات
$dbname = "ecommerce_db"; // اسم قاعدة البيانات

// إنشاء الاتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// تعيين ترميز الأحرف
$conn->set_charset("utf8mb4");

// إذا تم الاتصال بنجاح
echo "Connected successfully!";
?>
