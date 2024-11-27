<?php
// تضمين الاتصال بقاعدة البيانات
include 'db_connect.php';

// تأكيد إذا كانت هناك عملية شراء مبدئية أو أنه تم إرسال طلب فعلي
if (isset($_GET['order_id'])) {
    // استلام معرف الطلب من الرابط
    $order_id = $_GET['order_id'];

    // استعلام لاسترجاع تفاصيل الطلب من قاعدة البيانات
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = :order_id");
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();

    // إذا تم العثور على الطلب
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        // استعلام لاسترجاع تفاصيل المنتجات في الطلب
        $stmt_items = $conn->prepare("
            SELECT oi.*, p.name, p.price 
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = :order_id
        ");
        $stmt_items->bindParam(':order_id', $order_id);
        $stmt_items->execute();
        $order_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

        // تحديث حالة الطلب إلى "مؤكد"
        $update_status = $conn->prepare("UPDATE orders SET status = 'confirmed' WHERE id = :order_id");
        $update_status->bindParam(':order_id', $order_id);
        $update_status->execute();

        // عرض تفاصيل الطلب
        echo "<h1>تأكيد الطلب</h1>";
        echo "<p>رقم الطلب: " . $order['id'] . "</p>";
        echo "<p>حالة الطلب: " . $order['status'] . "</p>";
        echo "<p>تاريخ الطلب: " . $order['created_at'] . "</p>";
        echo "<h3>تفاصيل المنتجات في الطلب:</h3>";
        echo "<table border='1'>
                <tr>
                    <th>المنتج</th>
                    <th>السعر</th>
                    <th>الكمية</th>
                    <th>السعر الإجمالي</th>
                </tr>";
        $total_price = 0;
        foreach ($order_items as $item) {
            $total_item_price = $item['price'] * $item['quantity'];
            $total_price += $total_item_price;
            echo "<tr>
                    <td>" . $item['name'] . "</td>
                    <td>" . $item['price'] . " د.إ</td>
                    <td>" . $item['quantity'] . "</td>
                    <td>" . $total_item_price . " د.إ</td>
                  </tr>";
        }
        echo "</table>";
        echo "<p><strong>السعر الإجمالي: " . $total_price . " د.إ</strong></p>";

        // هنا يمكن إضافة معلومات إضافية مثل طرق الدفع، أو أي خطوات إضافية بعد الدفع.
        echo "<p>شكرًا لك على إتمام عملية الشراء!</p>";

    } else {
        echo "<p>الطلب غير موجود أو تم حذفه.</p>";
    }
} else {
    echo "<p>لا يمكن تحديد الطلب. يرجى التحقق من الرابط.</p>";
}

// إغلاق الاتصال بقاعدة البيانات
$conn = null;
?>
