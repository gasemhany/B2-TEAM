<?php
require_once 'php/auth.php';
require_once 'php/products.php';

$category = isset($_GET['category']) ? $_GET['category'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

if ($search) {
    $result = $product->searchProducts($search);
} else {
    $result = $product->getAllProducts($category, $limit, $offset);
}

$products = $result['success'] ? $result['products'] : [];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المنتجات - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .products-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .filters {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .search-box {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .category-filter {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .category-btn {
            padding: 5px 15px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }
        .category-btn.active {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .product-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .product-info {
            padding: 15px;
        }
        .product-title {
            margin: 0 0 10px 0;
            font-size: 1.2em;
        }
        .product-price {
            color: #28a745;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .add-to-cart {
            width: 100%;
            padding: 8px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .add-to-cart:hover {
            background: #0056b3;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        .page-link {
            padding: 5px 10px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .page-link.active {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="products-container">
        <div class="filters">
            <form method="GET" action="">
                <input type="text" name="search" class="search-box" placeholder="ابحث عن منتج..." value="<?php echo htmlspecialchars($search); ?>">
                <div class="category-filter">
                    <button type="submit" name="category" value="" class="category-btn <?php echo !$category ? 'active' : ''; ?>">الكل</button>
                    <button type="submit" name="category" value="electronics" class="category-btn <?php echo $category === 'electronics' ? 'active' : ''; ?>">إلكترونيات</button>
                    <button type="submit" name="category" value="clothing" class="category-btn <?php echo $category === 'clothing' ? 'active' : ''; ?>">ملابس</button>
                    <button type="submit" name="category" value="books" class="category-btn <?php echo $category === 'books' ? 'active' : ''; ?>">كتب</button>
                </div>
            </form>
        </div>

        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div class="product-price"><?php echo number_format($product['price'], 2); ?> ريال</div>
                        <button class="add-to-cart" onclick="addToCart(<?php echo $product['id']; ?>)">أضف إلى السلة</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&category=<?php echo $category; ?>&search=<?php echo urlencode($search); ?>" class="page-link">السابق</a>
            <?php endif; ?>
            
            <span class="page-link active"><?php echo $page; ?></span>
            
            <?php if (count($products) === $limit): ?>
                <a href="?page=<?php echo $page + 1; ?>&category=<?php echo $category; ?>&search=<?php echo urlencode($search); ?>" class="page-link">التالي</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function addToCart(productId) {
            fetch('php/cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=add&product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تمت إضافة المنتج إلى السلة');
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء إضافة المنتج إلى السلة');
            });
        }
    </script>
</body>
</html> 