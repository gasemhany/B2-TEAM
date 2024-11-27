<?php
// إعدادات الاتصال بقاعدة البيانات
$servername = "localhost"; // أو يمكن أن يكون "127.0.0.1"
$username = "root"; // اسم المستخدم لقاعدة البيانات
$password = ""; // كلمة المرور
$dbname = "b2"; // اسم قاعدة البيانات

// إنشاء الاتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// استلام البيانات من النموذج
$name = $_POST['name'];
$email = $_POST['email'];
$address = $_POST['address'];
$paymentMethod = $_POST['payment-method'];

// تأكيد إذا كان المستخدم اختار "Vodafone" أو "Credit Card" أو "Cash on Delivery"
$vodafoneNumber = isset($_POST['vodafone-number']) ? $_POST['vodafone-number'] : null;
$cardNumber = isset($_POST['card-number']) ? $_POST['card-number'] : null;
$expiryDate = isset($_POST['expiry-date']) ? $_POST['expiry-date'] : null;
$cvv = isset($_POST['cvv']) ? $_POST['cvv'] : null;

// إعداد استعلام لإدخال البيانات في جدول "orders"
$sql = "INSERT INTO orders (name, email, address, payment_method, vodafone_number, card_number, expiry_date, cvv)
VALUES ('$name', '$email', '$address', '$paymentMethod', '$vodafoneNumber', '$cardNumber', '$expiryDate', '$cvv')";

// تنفيذ الاستعلام
if ($conn->query($sql) === TRUE) {
    // إذا كانت العملية ناجحة
    echo "Payment confirmed and order placed successfully!";
} else {
    // في حال حدوث خطأ
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// إغلاق الاتصال
$conn->close();
?>
