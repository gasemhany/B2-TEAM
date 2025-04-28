<?php
require_once 'db_connect.php';
require_once 'config.php';
require_once 'auth.php';

class Cart {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addToCart($product_id, $quantity = 1) {
        if (!$auth->isLoggedIn()) {
            return ['success' => false, 'message' => 'يجب تسجيل الدخول لإضافة المنتجات إلى السلة'];
        }

        // التحقق من توفر المنتج
        $product = $this->conn->prepare("SELECT stock_quantity FROM products WHERE id = ?");
        $product->bind_param("i", $product_id);
        $product->execute();
        $result = $product->get_result();
        
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'المنتج غير موجود'];
        }

        $stock = $result->fetch_assoc()['stock_quantity'];
        if ($stock < $quantity) {
            return ['success' => false, 'message' => 'الكمية المطلوبة غير متوفرة'];
        }

        // التحقق من وجود المنتج في السلة
        $check = $this->conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $check->bind_param("ii", $_SESSION['user_id'], $product_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            // تحديث الكمية
            $cart_item = $result->fetch_assoc();
            $new_quantity = $cart_item['quantity'] + $quantity;
            
            if ($new_quantity > $stock) {
                return ['success' => false, 'message' => 'الكمية المطلوبة غير متوفرة'];
            }

            $update = $this->conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            $update->bind_param("ii", $new_quantity, $cart_item['id']);
            $update->execute();
        } else {
            // إضافة منتج جديد إلى السلة
            $insert = $this->conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $insert->bind_param("iii", $_SESSION['user_id'], $product_id, $quantity);
            $insert->execute();
        }

        return ['success' => true, 'message' => 'تمت إضافة المنتج إلى السلة'];
    }

    public function removeFromCart($product_id) {
        if (!$auth->isLoggedIn()) {
            return ['success' => false, 'message' => 'يجب تسجيل الدخول لإزالة المنتجات من السلة'];
        }

        $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $_SESSION['user_id'], $product_id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'تمت إزالة المنتج من السلة'];
        } else {
            return ['success' => false, 'message' => 'فشل في إزالة المنتج من السلة'];
        }
    }

    public function updateQuantity($product_id, $quantity) {
        if (!$auth->isLoggedIn()) {
            return ['success' => false, 'message' => 'يجب تسجيل الدخول لتحديث السلة'];
        }

        if ($quantity <= 0) {
            return $this->removeFromCart($product_id);
        }

        // التحقق من توفر الكمية
        $product = $this->conn->prepare("SELECT stock_quantity FROM products WHERE id = ?");
        $product->bind_param("i", $product_id);
        $product->execute();
        $result = $product->get_result();
        
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'المنتج غير موجود'];
        }

        $stock = $result->fetch_assoc()['stock_quantity'];
        if ($stock < $quantity) {
            return ['success' => false, 'message' => 'الكمية المطلوبة غير متوفرة'];
        }

        $stmt = $this->conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $_SESSION['user_id'], $product_id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'تم تحديث الكمية بنجاح'];
        } else {
            return ['success' => false, 'message' => 'فشل في تحديث الكمية'];
        }
    }

    public function getCart() {
        if (!$auth->isLoggedIn()) {
            return ['success' => false, 'message' => 'يجب تسجيل الدخول لعرض السلة'];
        }

        $stmt = $this->conn->prepare("
            SELECT c.id, c.quantity, p.id as product_id, p.name, p.price, p.image_url 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?
        ");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $items = [];
        $total = 0;
        
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
            $total += $row['price'] * $row['quantity'];
        }
        
        return [
            'success' => true,
            'items' => $items,
            'total' => $total
        ];
    }

    public function clearCart() {
        if (!$auth->isLoggedIn()) {
            return ['success' => false, 'message' => 'يجب تسجيل الدخول لتفريغ السلة'];
        }

        $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'تم تفريغ السلة بنجاح'];
        } else {
            return ['success' => false, 'message' => 'فشل في تفريغ السلة'];
        }
    }
}

// إنشاء كائن السلة
$cart = new Cart($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>
    <header>
        <div class="logo">B2 STORE</div>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="cart.html" class="active">Cart</a></li>
            </ul>
        </nav>
    </header>

    <section class="cart-section">
        <h1>Your Shopping Cart</h1>
        <div class="cart-container">
            <div class="cart-item">
                <img src="IMG/product1.jpg" alt="Product 1">
                <div class="item-details">
                    <h2>Product 1</h2>
                    <p>Price: $20</p>
                    <p>Quantity:</p>
                    <input type="number" value="1" min="1">
                </div>
                <button class="remove-btn">Remove</button>
            </div>

            <div class="cart-item">
                <img src="IMG/product2.jpg" alt="Product 2">
                <div class="item-details">
                    <h2>Product 2</h2>
                    <p>Price: $35</p>
                    <p>Quantity:</p>
                    <input type="number" value="1" min="1">
                </div>
                <button class="remove-btn">Remove</button>
            </div>

            <div class="cart-summary">
                <h2>Summary</h2>
                <p>Subtotal: 55</p>
                <p>Tax: 5</p>
                <p><strong>Total: 60</strong></p>
                <button class="checkout-btn">Checkout</button>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 B2 Store. All Rights Reserved.</p>
    </footer>

    <script src="cart.js"></script>
    
</body>
</html>
