<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $payment_status = $_POST['payment_status'];

    // التحقق من وجود الطلب في قاعدة البيانات
    $stmt_check_order = $conn->prepare("SELECT id FROM orders WHERE id = ?");
    $stmt_check_order->bind_param("i", $order_id);
    $stmt_check_order->execute();
    $result = $stmt_check_order->get_result();

    if ($result->num_rows === 0) {
        echo "Error: Order not found.";
        $stmt_check_order->close();
        $conn->close();
        exit();
    }

    // التحقق من حالة الدفع
    $valid_statuses = ['Pending', 'Completed', 'Failed'];
    if (!in_array($payment_status, $valid_statuses)) {
        echo "Error: Invalid payment status.";
        $stmt_check_order->close();
        $conn->close();
        exit();
    }

    // تحديث حالة الدفع
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $payment_status, $order_id);

    if ($stmt->execute()) {
        echo "Payment processed for Order ID: " . $order_id;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $stmt_check_order->close();
}
$conn->close();
?>
