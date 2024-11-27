<?php
require 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $address = $_POST['address'] ?? '';
        $paymentMethod = $_POST['payment_method'] ?? '';
        $vodafoneNumber = $_POST['vodafone_number'] ?? null;
        $cardNumber = $_POST['card_number'] ?? null;
        $expiryDate = $_POST['expiry_date'] ?? null;
        $cvv = $_POST['cvv'] ?? null;

        try {
            $conn->beginTransaction();

            foreach ($cart as $item) {
                $stmt = $conn->prepare("
                    INSERT INTO orders (name, email, address, payment_method, vodafone_number, card_number, expiry_date, cvv)
                    VALUES (:name, :email, :address, :payment_method, :vodafone_number, :card_number, :expiry_date, :cvv)
                ");

                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':address' => $address,
                    ':payment_method' => $paymentMethod,
                    ':vodafone_number' => $vodafoneNumber,
                    ':card_number' => $cardNumber,
                    ':expiry_date' => $expiryDate,
                    ':cvv' => $cvv
                ]);
            }

            $conn->commit();
            unset($_SESSION['cart']);
            echo "Order placed successfully!";
        } catch (PDOException $e) {
            $conn->rollBack();
            die("Error processing order: " . $e->getMessage());
        }
    } else {
        die("Cart is empty. Cannot place order.");
    }
} else {
    die("Invalid request.");
}
?>
