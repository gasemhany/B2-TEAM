<?php
// معلومات الاتصال بقاعدة البيانات
$host = 'localhost'; // عنوان السيرفر (عادة يكون localhost)
$dbname = 'b2'; // اسم قاعدة البيانات
$username = 'root'; // اسم المستخدم لقاعدة البيانات
$password = ''; // كلمة مرور المستخدم (تكون فارغة في بيئات مثل XAMPP أو MAMP)

try {
    // إنشاء الاتصال باستخدام PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // تعيين إعدادات PDO لإظهار الأخطاء إذا حدثت
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // رسالة اختبار للاتصال الناجح
    // echo "تم الاتصال بنجاح!";
} catch (PDOException $e) {
    // إذا فشل الاتصال، يتم عرض رسالة الخطأ
    echo "فشل الاتصال بقاعدة البيانات: " . $e->getMessage();
}
?>
