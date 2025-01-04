<?php
// اتصال بقاعدة البيانات
$conn = new mysqli('localhost', 'root', '', 'b2store'); // تعديل القيم حسب إعداداتك

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// التحقق من وجود معرّف المنتج
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // جلب بيانات المنتج من قاعدة البيانات
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("Product not found.");
    }
} else {
    die("Invalid product ID.");
}

// معالجة بيانات النموذج عند الإرسال
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];

    // تحديث بيانات المنتج
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $image_name = $_FILES['product_image']['name'];
        $image_tmp_name = $_FILES['product_image']['tmp_name'];
        $upload_dir = "uploads/";
        $target_file = $upload_dir . basename($image_name);

        // رفع الصورة الجديدة
        if (move_uploaded_file($image_tmp_name, $target_file)) {
            $sql = "UPDATE products SET 
                        name = '$product_name', 
                        price = '$price', 
                        description = '$description', 
                        quantity = '$quantity', 
                        image = '$target_file' 
                    WHERE id = $product_id";
        } else {
            echo "<script>alert('Error uploading new image.');</script>";
        }
    } else {
        $sql = "UPDATE products SET 
                    name = '$product_name', 
                    price = '$price', 
                    description = '$description', 
                    quantity = '$quantity' 
                WHERE id = $product_id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Product updated successfully!');</script>";
        header("Location: admin_dashboard.php"); // إعادة توجيه بعد التحديث
        exit;
    } else {
        echo "<script>alert('Error updating product: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #000000, #4a0000);
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #222;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        .container h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        form label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        form input, form textarea, form button {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            border: none;
        }
        form input, form textarea {
            background: #333;
            color: #fff;
        }
        form button {
            background: #f2a365;
            color: #000;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        form button:hover {
            background: #d18b4a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Product</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" value="<?= $product['name'] ?>" required>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" value="<?= $product['price'] ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required><?= $product['description'] ?></textarea>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="<?= $product['quantity'] ?>" required>

            <label for="product_image">Product Image:</label>
            <input type="file" id="product_image" name="product_image" accept="image/*">
            <p>Current Image:</p>
            <img src="<?= $product['image'] ?>" alt="Product Image" style="width: 100px; height: auto;">

            <button type="submit">Update Product</button>
        </form>
    </div>
</body>
</html>
