<?php
// الاتصال بقاعدة البيانات
$host = 'localhost';
$dbname = 'b2';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // استلام بيانات الطلب
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $payment = $_POST['payment'];

    // متغيرات إضافية بناءً على طريقة الدفع
    $card_number = $payment === 'credit_card' ? $_POST['card_number'] : null;
    $expiry_date = $payment === 'credit_card' ? $_POST['expiry_date'] : null;
    $cvv = $payment === 'credit_card' ? $_POST['cvv'] : null;

    $vodafone_number = $payment === 'vodafone_cash' ? $_POST['vodafone_number'] : null;

    // حفظ البيانات في جدول الطلبات
    $sql = "INSERT INTO orders (name, email, address, payment_method, card_number, expiry_date, cvv, vodafone_number)
            VALUES (:name, :email, :address, :payment, :card_number, :expiry_date, :cvv, :vodafone_number)";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':payment', $payment);
    $stmt->bindParam(':card_number', $card_number);
    $stmt->bindParam(':expiry_date', $expiry_date);
    $stmt->bindParam(':cvv', $cvv);
    $stmt->bindParam(':vodafone_number', $vodafone_number);

    $stmt->execute();

    echo "Order placed successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
