<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // استقبال البيانات من النموذج
    $products = $_POST['products'];
    $quantities = $_POST['quantities'];

    // التحقق من صحة البيانات
    if (count($products) !== count($quantities)) {
        die("حدث خطأ في البيانات المرسلة.");
    }

    // عرض المنتجات والكميات
    echo "<h1>قائمة المنتجات المستلمة:</h1>";
    echo "<ul>";
    foreach ($products as $index => $product) {
        $quantity = htmlspecialchars($quantities[$index]);
        $product = htmlspecialchars($product);
        echo "<li>$product: $quantity</li>";
    }
    echo "</ul>";
} else {
    echo "لا توجد بيانات مستلمة.";
}
?>
