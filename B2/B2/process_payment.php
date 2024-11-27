<?php
// تضمين الاتصال بقاعدة البيانات
include 'db_connect.php';

// التحقق مما إذا كان الطلب موجودًا
if (isset($_POST['order_id']) && isset($_POST['payment_method'])) {
    $order_id = $_POST['order_id'];
    $payment_method = $_POST['payment_method'];  // مثل PayPal، Credit Card، أو أي طريقة دفع أخرى
    $payment_status = "pending";  // حالة الدفع مبدئيًا تكون معلقة

    // استعلام للتحقق من وجود الطلب في قاعدة البيانات
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = :order_id");
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        // هنا يمكنك إضافة الكود الخاص بإتمام الدفع باستخدام بوابة الدفع (مثل PayPal أو Stripe)
        // على سبيل المثال، إذا كانت عملية الدفع تم تنفيذها بنجاح، نقوم بتحديث حالة الدفع
        // في هذا المثال، سنعتبر الدفع ناجحًا دائمًا كما لو كان عملية وهمية:
        $payment_status = "completed";  // التحديث إلى "تم الدفع"

        // تحديث حالة الطلب في قاعدة البيانات إلى "تم الدفع"
        $update_status = $conn->prepare("UPDATE orders SET status = 'completed', payment_method = :payment_method WHERE id = :order_id");
        $update_status->bindParam(':order_id', $order_id);
        $update_status->bindParam(':payment_method', $payment_method);
        $update_status->execute();

        // إرسال رسالة تأكيد للمستخدم
        echo "<h1>تمت عملية الدفع بنجاح!</h1>";
        echo "<p>تم استلام مدفوعاتك بنجاح. شكرًا لك على شراء منتجاتنا.</p>";
        echo "<p>رقم الطلب: " . $order['id'] . "</p>";
        echo "<p>حالة الدفع: " . $payment_status . "</p>";
        echo "<p>طريقة الدفع: " . $payment_method . "</p>";

        // إرسال بريد إلكتروني تأكيد للمستخدم (اختياري)
        // mail($user_email, "تأكيد الدفع", "لقد تم الدفع بنجاح لطلبك رقم: $order_id");

    } else {
        echo "<p>الطلب غير موجود.</p>";
    }
} else {
    echo "<p>الرجاء التحقق من تفاصيل الطلب ووسيلة الدفع.</p>";
}

// إغلاق الاتصال بقاعدة البيانات
$conn = null;
?>
