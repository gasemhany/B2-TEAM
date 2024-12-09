<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $total_amount = $_POST['total_amount'];
    $status = "Pending";

    // التحقق من وجود العميل في قاعدة البيانات
    $stmt_check_customer = $conn->prepare("SELECT id FROM customers WHERE id = ?");
    $stmt_check_customer->bind_param("i", $customer_id);
    $stmt_check_customer->execute();
    $result = $stmt_check_customer->get_result();

    if ($result->num_rows === 0) {
        echo "Error: Customer not found.";
        $stmt_check_customer->close();
        $conn->close();
        exit();
    }

    // إضافة الطلب
    $stmt = $conn->prepare("INSERT INTO orders (customer_id, total_amount, status) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $customer_id, $total_amount, $status);

    if ($stmt->execute()) {
        echo "Order placed successfully! Order ID: " . $stmt->insert_id;
    } else {
        echo "Error: " . $stmt->error;
    }

    // إغلاق البيانات المفتوحة
    $stmt->close();
    $stmt_check_customer->close();
}

$conn->close();
?>
