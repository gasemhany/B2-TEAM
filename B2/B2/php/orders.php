<?php
require_once 'db_connect.php';
require_once 'config.php';
require_once 'auth.php';
require_once 'cart.php';

class Order {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function createOrder($shipping_address, $payment_method) {
        if (!$auth->isLoggedIn()) {
            return ['success' => false, 'message' => 'يجب تسجيل الدخول لإنشاء طلب'];
        }

        // الحصول على محتويات السلة
        $cart_result = $cart->getCart();
        if (!$cart_result['success'] || empty($cart_result['items'])) {
            return ['success' => false, 'message' => 'السلة فارغة'];
        }

        // بدء المعاملة
        $this->conn->begin_transaction();

        try {
            // إنشاء الطلب
            $stmt = $this->conn->prepare("
                INSERT INTO orders (user_id, total_amount, shipping_address, payment_method) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param("idss", $_SESSION['user_id'], $cart_result['total'], $shipping_address, $payment_method);
            $stmt->execute();
            $order_id = $this->conn->insert_id;

            // إضافة عناصر الطلب
            foreach ($cart_result['items'] as $item) {
                // التحقق من توفر الكمية
                $product = $this->conn->prepare("SELECT stock_quantity FROM products WHERE id = ?");
                $product->bind_param("i", $item['product_id']);
                $product->execute();
                $result = $product->get_result();
                $stock = $result->fetch_assoc()['stock_quantity'];

                if ($stock < $item['quantity']) {
                    throw new Exception("الكمية المطلوبة غير متوفرة للمنتج: " . $item['name']);
                }

                // تحديث المخزون
                $update = $this->conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
                $update->bind_param("ii", $item['quantity'], $item['product_id']);
                $update->execute();

                // إضافة عنصر الطلب
                $insert = $this->conn->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity, price) 
                    VALUES (?, ?, ?, ?)
                ");
                $insert->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
                $insert->execute();
            }

            // تفريغ السلة
            $cart->clearCart();

            // تأكيد المعاملة
            $this->conn->commit();

            return [
                'success' => true,
                'message' => 'تم إنشاء الطلب بنجاح',
                'order_id' => $order_id
            ];
        } catch (Exception $e) {
            // إلغاء المعاملة في حالة حدوث خطأ
            $this->conn->rollback();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getOrder($order_id) {
        if (!$auth->isLoggedIn()) {
            return ['success' => false, 'message' => 'يجب تسجيل الدخول لعرض الطلب'];
        }

        $stmt = $this->conn->prepare("
            SELECT o.*, oi.quantity, oi.price, p.name as product_name, p.image_url 
            FROM orders o 
            JOIN order_items oi ON o.id = oi.order_id 
            JOIN products p ON oi.product_id = p.id 
            WHERE o.id = ? AND o.user_id = ?
        ");
        $stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'الطلب غير موجود'];
        }

        $order = [
            'id' => $order_id,
            'items' => [],
            'total' => 0
        ];

        while ($row = $result->fetch_assoc()) {
            if (!isset($order['shipping_address'])) {
                $order['shipping_address'] = $row['shipping_address'];
                $order['payment_method'] = $row['payment_method'];
                $order['status'] = $row['status'];
                $order['created_at'] = $row['created_at'];
            }
            
            $order['items'][] = [
                'name' => $row['product_name'],
                'quantity' => $row['quantity'],
                'price' => $row['price'],
                'image_url' => $row['image_url']
            ];
            
            $order['total'] += $row['price'] * $row['quantity'];
        }

        return ['success' => true, 'order' => $order];
    }

    public function getUserOrders() {
        if (!$auth->isLoggedIn()) {
            return ['success' => false, 'message' => 'يجب تسجيل الدخول لعرض الطلبات'];
        }

        $stmt = $this->conn->prepare("
            SELECT o.*, COUNT(oi.id) as items_count 
            FROM orders o 
            LEFT JOIN order_items oi ON o.id = oi.order_id 
            WHERE o.user_id = ? 
            GROUP BY o.id 
            ORDER BY o.created_at DESC
        ");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        return ['success' => true, 'orders' => $orders];
    }

    public function updateOrderStatus($order_id, $status) {
        if (!$auth->isAdmin()) {
            return ['success' => false, 'message' => 'غير مصرح لك بتحديث حالة الطلب'];
        }

        $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'تم تحديث حالة الطلب بنجاح'];
        } else {
            return ['success' => false, 'message' => 'فشل في تحديث حالة الطلب'];
        }
    }
}

// إنشاء كائن الطلبات
$order = new Order($conn);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبات العميل</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <header>
        <h1>قائمة الطلبات</h1>
    </header>

    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>اسم العميل</th>
                    <th>المنتج</th>
                    <th>الكمية</th>
                    <th>التاريخ</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // التحقق إذا كان هناك نتائج من الاستعلام
                if ($result->num_rows > 0) {
                    // عرض كل صف من نتائج الاستعلام
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["order_id"] . "</td>
                                <td>" . $row["customer_name"] . "</td>
                                <td>" . $row["product"] . "</td>
                                <td>" . $row["quantity"] . "</td>
                                <td>" . $row["order_date"] . "</td>
                                <td>" . $row["status"] . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>لا توجد طلبات لعرضها</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <footer class="footer">
        <p>جميع الحقوق محفوظة &copy; 2024</p>
    </footer>

</body>
</html>

<?php
// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>
