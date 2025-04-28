<?php
// إعدادات الموقع
define('SITE_NAME', 'متجر B2');
define('SITE_URL', 'http://localhost/B2');

// إعدادات البريد الإلكتروني
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-password');

// إعدادات الدفع
define('PAYMENT_GATEWAY', 'stripe'); // أو 'paypal' أو أي بوابة دفع أخرى
define('STRIPE_PUBLIC_KEY', 'your-stripe-public-key');
define('STRIPE_SECRET_KEY', 'your-stripe-secret-key');

// إعدادات التخزين
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

// إعدادات الأمان
define('SESSION_LIFETIME', 3600); // ساعة واحدة
define('PASSWORD_HASH_COST', 12);

// رسائل الخطأ
$error_messages = [
    'login_failed' => 'اسم المستخدم أو كلمة المرور غير صحيحة',
    'register_failed' => 'فشل في إنشاء الحساب',
    'product_not_found' => 'المنتج غير موجود',
    'cart_empty' => 'سلة المشتريات فارغة',
    'payment_failed' => 'فشل في عملية الدفع',
    'order_failed' => 'فشل في إنشاء الطلب'
];
?> 