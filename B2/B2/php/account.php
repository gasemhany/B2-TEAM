<?php
session_start(); // بدء الجلسة

require_once 'db_connect.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // إعادة التوجيه لصفحة تسجيل الدخول
    exit();
}

$user_id = $_SESSION['user_id'];

// جلب معلومات المستخدم من قاعدة البيانات
$stmt = $conn->prepare("SELECT full_name, email, phone_number, address FROM customers WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// تحديث معلومات الحساب
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // التحقق من صحة المدخلات
    $full_name = trim($_POST['full_name']);
    $phone_number = trim($_POST['phone_number']);
    $address = trim($_POST['address']);

    // التحقق من أن رقم الهاتف يتكون من أرقام فقط (اختياري)
    if (!empty($phone_number) && !preg_match("/^\d+$/", $phone_number)) {
        echo "Invalid phone number.";
        exit();
    }

    // تحديث قاعدة البيانات
    $update_stmt = $conn->prepare("UPDATE customers SET full_name = ?, phone_number = ?, address = ? WHERE id = ?");
    $update_stmt->bind_param("sssi", $full_name, $phone_number, $address, $user_id);

    if ($update_stmt->execute()) {
        echo "Account updated successfully!";
    } else {
        echo "Error updating account: " . $update_stmt->error;
    }

    $update_stmt->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
</head>
<body>
    <h1>My Account</h1>

    <!-- نموذج تحديث الحساب -->
    <form action="account.php" method="POST">
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly><br><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>"><br><br>

        <label for="address">Address:</label>
        <textarea id="address" name="address"><?= htmlspecialchars($user['address']) ?></textarea><br><br>

        <button type="submit">Update Account</button>
    </form>

    <br>
    <a href="logout.php">Logout</a>
</body>
</html>
