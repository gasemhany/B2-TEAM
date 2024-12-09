<?php
// إعدادات الاتصال بقاعدة البيانات
$servername = "23.88.73.88"; // عنوان الخادم (يمكن تغييره إذا كان localhost)
$username = "u2719155_XZObLAYBtO"; // اسم المستخدم لقاعدة البيانات
$password = "z4f!J@wYWsPHW.L1z9yZF6WI"; // كلمة المرور لقاعدة البيانات
$dbname = "s2719155_Bb2"; // اسم قاعدة البيانات

// إنشاء الاتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// إذا تم الاتصال بنجاح
echo "Connected successfully!";
?>
