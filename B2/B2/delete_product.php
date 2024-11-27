<?php
// تضمين الاتصال بقاعدة البيانات
include 'db_connect.php';

// التحقق من وجود معرّف المنتج في الرابط
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // استعلام لحذف المنتج
    $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
    $stmt->bindParam(':id', $product_id);

    if ($stmt->execute()) {
        echo "<p>تم حذف المنتج بنجاح!</p>";
    } else {
        echo "<p>حدث خطأ أثناء حذف المنتج.</p>";
    }
} else {
    echo "<p>معرف المنتج مفقود.</p>";
}

// إغلاق الاتصال بقاعدة البيانات
$conn = null;
?>
