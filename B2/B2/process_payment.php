require 'vendor/autoload.php'; // تحميل مكتبة Stripe

Stripe\Stripe::setApiKey('sk_test_your_secret_key'); // ضع مفتاحك السري

 try {
    $charge = \Stripe\Charge::create([
        'amount' => $_POST['amount'] * 100, // تحويل إلى سنتات
        'currency' => 'usd',
        'source' => 'tok_visa', // رمز المصدر (يمكن استبداله برمز العميل الفعلي)
        'description' => 'وصف المعاملة',
    ]);

    echo 'تمت عملية الدفع بنجاح: ' . $charge->id;
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo 'فشل الدفع: ' . $e->getMessage();
}
