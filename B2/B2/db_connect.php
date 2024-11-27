<?php
// إعدادات الاتصال بقاعدة البيانات
$host = "localhost"; // عنوان الخادم
$username = "root";  // اسم المستخدم الافتراضي
$password = "";      // كلمة المرور الافتراضية (اتركها فارغة في XAMPP)
$database = "b2"; // اسم قاعدة البيانات

try {
    // إنشاء اتصال بقاعدة البيانات باستخدام PDO
    $conn = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $username, $password);
    // تفعيل وضع الأخطاء لتسهيل تصحيح الأخطاء
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // عرض رسالة خطأ عند فشل الاتصال
    die("Database connection failed: " . $e->getMessage());
}
?>
