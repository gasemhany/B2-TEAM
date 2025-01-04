<?php
session_start(); // بدء الجلسة

require_once 'db_connect.php';

// التحقق إذا تم إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // جلب بيانات المستخدم من قاعدة البيانات
    $stmt = $conn->prepare("SELECT id, password FROM customers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // التحقق من كلمة المرور
        if (password_verify($password, $user['password'])) {
            // تخزين بيانات المستخدم في الجلسة
            $_SESSION['user_id'] = $user['id'];
            header('Location: account.php'); // إعادة التوجيه لصفحة الحساب
            exit();
        } else {
            $error = "Invalid email or password."; // رسالة خطأ عند عدم تطابق كلمة المرور
        }
    } else {
        $error = "Invalid email or password."; // رسالة خطأ عند عدم وجود البريد الإلكتروني
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="loginpanal.css.css">
</head>
<body>
    <h1>Login</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</body>
</html>
