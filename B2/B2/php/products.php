<?php
require_once 'db_connect.php';
require_once 'config.php';
require_once 'auth.php';

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// جلب المنتجات من قاعدة البيانات
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

class Product {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addProduct($name, $description, $price, $image_url, $stock_quantity, $category) {
        if (!$auth->isAdmin()) {
            return ['success' => false, 'message' => 'غير مصرح لك بإضافة منتجات'];
        }

        $stmt = $this->conn->prepare("INSERT INTO products (name, description, price, image_url, stock_quantity, category) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsis", $name, $description, $price, $image_url, $stock_quantity, $category);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'تم إضافة المنتج بنجاح', 'product_id' => $stmt->insert_id];
        } else {
            return ['success' => false, 'message' => 'فشل في إضافة المنتج'];
        }
    }

    public function updateProduct($id, $name, $description, $price, $image_url, $stock_quantity, $category) {
        if (!$auth->isAdmin()) {
            return ['success' => false, 'message' => 'غير مصرح لك بتعديل المنتجات'];
        }

        $stmt = $this->conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image_url = ?, stock_quantity = ?, category = ? WHERE id = ?");
        $stmt->bind_param("ssdsisi", $name, $description, $price, $image_url, $stock_quantity, $category, $id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'تم تحديث المنتج بنجاح'];
        } else {
            return ['success' => false, 'message' => 'فشل في تحديث المنتج'];
        }
    }

    public function deleteProduct($id) {
        if (!$auth->isAdmin()) {
            return ['success' => false, 'message' => 'غير مصرح لك بحذف المنتجات'];
        }

        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'تم حذف المنتج بنجاح'];
        } else {
            return ['success' => false, 'message' => 'فشل في حذف المنتج'];
        }
    }

    public function getProduct($id) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return ['success' => true, 'product' => $result->fetch_assoc()];
        } else {
            return ['success' => false, 'message' => 'المنتج غير موجود'];
        }
    }

    public function getAllProducts($category = null, $limit = 20, $offset = 0) {
        if ($category) {
            $stmt = $this->conn->prepare("SELECT * FROM products WHERE category = ? LIMIT ? OFFSET ?");
            $stmt->bind_param("sii", $category, $limit, $offset);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM products LIMIT ? OFFSET ?");
            $stmt->bind_param("ii", $limit, $offset);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        
        return ['success' => true, 'products' => $products];
    }

    public function searchProducts($query) {
        $search = "%$query%";
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        
        return ['success' => true, 'products' => $products];
    }
}

// إنشاء كائن المنتجات
$product = new Product($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
</head>
<body>
    <h1>Products</h1>

    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Category</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= htmlspecialchars($row['price']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No products found</p>
    <?php endif; ?>
</body>
</html>

<?php
// إغلاق الاتصال بقاعدة البيانات
$conn->close();
?>
