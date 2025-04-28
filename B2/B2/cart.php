<?php
require_once 'php/auth.php';
require_once 'php/cart.php';

if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$cart_result = $cart->getCart();
$items = $cart_result['success'] ? $cart_result['items'] : [];
$total = $cart_result['success'] ? $cart_result['total'] : 0;
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سلة المشتريات - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .cart-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        .cart-items {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .cart-item {
            display: flex;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
            margin-left: 15px;
        }
        .item-details {
            flex: 1;
        }
        .item-title {
            margin: 0 0 10px 0;
            font-size: 1.1em;
        }
        .item-price {
            color: #28a745;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .quantity-btn {
            padding: 5px 10px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }
        .quantity-input {
            width: 50px;
            text-align: center;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .remove-btn {
            padding: 5px 10px;
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .cart-summary {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .total-row {
            font-weight: bold;
            font-size: 1.2em;
            border-top: 1px solid #eee;
            padding-top: 10px;
            margin-top: 10px;
        }
        .checkout-btn {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        .checkout-btn:hover {
            background: #218838;
        }
        .empty-cart {
            text-align: center;
            padding: 40px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <h1>سلة المشتريات</h1>
        
        <?php if (empty($items)): ?>
            <div class="empty-cart">
                <h2>سلة المشتريات فارغة</h2>
                <p>قم بإضافة بعض المنتجات إلى سلة المشتريات</p>
                <a href="products.php" class="btn">تصفح المنتجات</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($items as $item): ?>
                    <div class="cart-item" id="item-<?php echo $item['id']; ?>">
                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="item-image">
                        <div class="item-details">
                            <h3 class="item-title"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <div class="item-price"><?php echo number_format($item['price'], 2); ?> ريال</div>
                            <div class="quantity-control">
                                <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] - 1; ?>)">-</button>
                                <input type="number" class="quantity-input" value="<?php echo $item['quantity']; ?>" min="1" onchange="updateQuantity(<?php echo $item['id']; ?>, this.value)">
                                <button class="quantity-btn" onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] + 1; ?>)">+</button>
                            </div>
                        </div>
                        <button class="remove-btn" onclick="removeItem(<?php echo $item['id']; ?>)">حذف</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <div class="summary-row">
                    <span>المجموع الفرعي</span>
                    <span><?php echo number_format($total, 2); ?> ريال</span>
                </div>
                <div class="summary-row">
                    <span>ضريبة القيمة المضافة (15%)</span>
                    <span><?php echo number_format($total * 0.15, 2); ?> ريال</span>
                </div>
                <div class="summary-row total-row">
                    <span>المجموع الكلي</span>
                    <span><?php echo number_format($total * 1.15, 2); ?> ريال</span>
                </div>
                <button class="checkout-btn" onclick="checkout()">إتمام الشراء</button>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function updateQuantity(itemId, quantity) {
            if (quantity < 1) return;
            
            fetch('php/cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=update&item_id=' + itemId + '&quantity=' + quantity
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء تحديث الكمية');
            });
        }

        function removeItem(itemId) {
            if (confirm('هل أنت متأكد من حذف هذا المنتج من السلة؟')) {
                fetch('php/cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=remove&item_id=' + itemId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('item-' + itemId).remove();
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء حذف المنتج');
                });
            }
        }

        function checkout() {
            window.location.href = 'checkout.php';
        }
    </script>
</body>
</html> 