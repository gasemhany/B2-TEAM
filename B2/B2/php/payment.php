<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Payment Page</title>
    <style>
        .payment-details { display: none; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>Payment Page</h1>

    <form action="process_payment.php" method="POST">
        <!-- معلومات المستخدم -->
        <h3>Customer Details</h3>
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br>

        <!-- اختيار طريقة الدفع -->
        <h3>Payment Method</h3>
        <label>
            <input type="radio" name="payment" value="credit_card" id="credit_card_option" required>
            Credit Card
        </label><br>
        <label>
            <input type="radio" name="payment" value="vodafone_cash" id="vodafone_cash_option" required>
            Vodafone Cash
        </label><br>
        <label>
            <input type="radio" name="payment" value="cash_on_delivery" id="cash_on_delivery_option" required>
            Cash on Delivery
        </label><br>

        <!-- تفاصيل الكريديت كارد -->
        <div id="credit_card_details" class="payment-details">
            <h4>Credit Card Details</h4>
            <label for="card_number">Card Number:</label>
            <input type="text" id="card_number" name="card_number" placeholder="Enter your card number"><br>

            <label for="expiry_date">Expiry Date:</label>
            <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY"><br>

            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" placeholder="123"><br>
        </div>

        <!-- تفاصيل فودافون كاش -->
        <div id="vodafone_cash_details" class="payment-details">
            <h4>Vodafone Cash Details</h4>
            <label for="vodafone_number">Vodafone Cash Number:</label>
            <input type="text" id="vodafone_number" name="vodafone_number" placeholder="Enter your Vodafone number"><br>
        </div>

        <!-- الدفع عند التوصيل (لا تفاصيل إضافية) -->
        <div id="cash_on_delivery_details" class="payment-details">
            <p>No additional details required for Cash on Delivery.</p>
        </div>

        <button type="submit">Place Order</button>
    </form>

    <script>
        // عرض الحقول المناسبة بناءً على اختيار المستخدم
        const paymentOptions = document.querySelectorAll('input[name="payment"]');
        const creditCardDetails = document.getElementById('credit_card_details');
        const vodafoneCashDetails = document.getElementById('vodafone_cash_details');
        const cashOnDeliveryDetails = document.getElementById('cash_on_delivery_details');

        paymentOptions.forEach(option => {
            option.addEventListener('change', () => {
                creditCardDetails.style.display = option.value === 'credit_card' ? 'block' : 'none';
                vodafoneCashDetails.style.display = option.value === 'vodafone_cash' ? 'block' : 'none';
                cashOnDeliveryDetails.style.display = option.value === 'cash_on_delivery' ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>
