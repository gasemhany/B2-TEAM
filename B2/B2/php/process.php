<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']); // حماية من XSS
    echo "مرحبًا، $name!";
}
?>
