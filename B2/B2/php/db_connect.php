<?php
// إعدادات الاتصال بقاعدة البيانات
$servername = "localhost"; // عنوان الخادم (يمكن تغييره إذا كان localhost)
$username = "root"; // اسم المستخدم لقاعدة البيانات
$password = ""; // كلمة المرور لقاعدة البيانات
$dbname = "test"; // اسم قاعدة البيانات

// إنشاء الاتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// إذا تم الاتصال بنجاح
echo "Connected successfully!";
?>
