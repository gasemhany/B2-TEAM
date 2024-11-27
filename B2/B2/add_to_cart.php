<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // التأكد من وجود معلومات المنتج
    if (isset($_POST['product_id'], $_POST['product_name'], $_POST['product_price'])) {
        $product = [
            'id' => $_POST['product_id'],
            'name' => $_POST['product_name'],
            'price' => $_POST['product_price'],
            'quantity' => $_POST['product_quantity'] ?? 1
        ];

        // إضافة المنتج إلى السلة
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // التحقق من وجود المنتج مسبقًا وزيادة الكمية
        $exists = false;
        foreach ($_SESSION['cart'] as &$cartItem) {
            if ($cartItem['id'] == $product['id']) {
                $cartItem['quantity'] += $product['quantity'];
                $exists = true;
                break;
            }
        }

        // إذا لم يكن المنتج موجودًا، إضافته إلى السلة
        if (!$exists) {
            $_SESSION['cart'][] = $product;
        }

        // رسالة نجاح
        echo json_encode(['status' => 'success', 'message' => 'Product added to cart.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid product data.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
