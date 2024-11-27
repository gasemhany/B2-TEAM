<?php
include 'db_connect.php';

// استلام ID الطلب
$order_id = $_GET['order_id'];

// استعلام لاسترجاع بيانات الطلب
$query = $conn->prepare("SELECT * FROM orders WHERE id = :order_id");
$query->execute(['order_id' => $order_id]);
$order = $query->fetch(PDO::FETCH_ASSOC);

if ($order) {
    echo "<h1>Order Confirmation</h1>";
    echo "<p>Thank you for your purchase!</p>";
    echo "<p>Product ID: " . $order['product_id'] . "</p>";
    echo "<p>Quantity: " . $order['quantity'] . "</p>";
    echo "<p>Total Price: " . $order['total_price'] . " LE</p>";
    echo "<p>Customer: " . $order['customer_name'] . "</p>";
    echo "<p>Email: " . $order['customer_email'] . "</p>";
} else {
    echo "<p>Order not found!</p>";
}
?>
