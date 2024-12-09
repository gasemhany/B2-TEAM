<?php
// بدء الجلسة للتحقق من حالة تسجيل الدخول
session_start();

// التحقق من أن المستخدم مشرف
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// إضافة الاتصال بقاعدة البيانات
require_once 'db_connect.php';

// استعلام للحصول على عدد المنتجات
$productCountQuery = "SELECT COUNT(*) FROM products";
$productCountResult = $conn->query($productCountQuery);
$productCount = $productCountResult->fetch_row()[0];

// استعلام للحصول على عدد الطلبات المعلقة
$orderCountQuery = "SELECT COUNT(*) FROM orders WHERE status='pending'";
$orderCountResult = $conn->query($orderCountQuery);
$orderCount = $orderCountResult->fetch_row()[0];

// استعلام للحصول على عدد المستخدمين المسجلين
$userCountQuery = "SELECT COUNT(*) FROM users";
$userCountResult = $conn->query($userCountQuery);
$userCount = $userCountResult->fetch_row()[0];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - B2 Store</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Resetting default margins */
        body, h1, h2, h3, p {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #000000, #4a0000);
            color: #fff;
        }
        header {
            background: #222;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #f2a365;
        }
        header nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }
        header nav ul li {
            display: inline;
        }
        header nav ul li a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
        }
        .container {
            display: flex;
            margin-top: 20px;
        }
        .sidebar {
            width: 20%;
            background: #1e1e1e;
            padding: 20px;
            min-height: 100vh;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 15px 0;
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .sidebar ul li a:hover {
            background: #f2a365;
            color: #000;
        }
        .main-content {
            width: 80%;
            padding: 20px;
        }
        .main-content h1 {
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .main-content .cards {
            display: flex;
            gap: 20px;
        }
        .card {
            background: #333;
            color: #fff;
            padding: 20px;
            border-radius: 10px;}