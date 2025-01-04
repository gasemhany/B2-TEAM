<?php
// الاتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";

// إنشاء الاتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// استعلام لجلب جميع الطلبات
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);

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
