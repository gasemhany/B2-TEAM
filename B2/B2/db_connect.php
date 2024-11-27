<?php
// إعدادات الاتصال بقاعدة البيانات
$servername = "localhost"; // عادة ما يكون localhost
$username = "root";        // اسم المستخدم (عادةً root في localhost)
$password = "";            // كلمة المرور (إذا لم يكن لديك كلمة مرور اتركها فارغة)
$dbname = "b2";            // اسم قاعدة البيانات التي قمت بإنشائها

try {
    // إنشاء الاتصال باستخدام PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // تعيين وضع الخطأ ليكون استثناءات
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // رسالة تأكيد إذا تم الاتصال بنجاح
    echo "تم الاتصال بقاعدة البيانات بنجاح";
}
catch (PDOException $e) {
    // في حالة حدوث خطأ
    echo "خطأ في الاتصال: " . $e->getMessage();
}
?>
