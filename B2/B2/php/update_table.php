<?php
$host = 'localhost';
$dbname = 'b2';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "ALTER TABLE orders 
            ADD COLUMN card_number VARCHAR(16) NULL,
            ADD COLUMN expiry_date VARCHAR(5) NULL,
            ADD COLUMN cvv VARCHAR(3) NULL,
            ADD COLUMN vodafone_number VARCHAR(15) NULL";
    
    $conn->exec($sql);
    echo "Table updated successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
