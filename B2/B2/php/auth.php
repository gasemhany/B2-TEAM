<?php
session_start();
require_once 'db_connect.php';
require_once 'config.php';

class Auth {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function register($username, $password, $email, $full_name, $address = '', $phone = '') {
        // التحقق من وجود المستخدم
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return ['success' => false, 'message' => 'اسم المستخدم أو البريد الإلكتروني موجود بالفعل'];
        }

        // تشفير كلمة المرور
        $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => PASSWORD_HASH_COST]);

        // إضافة المستخدم الجديد
        $stmt = $this->conn->prepare("INSERT INTO users (username, password, email, full_name, address, phone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $hashed_password, $email, $full_name, $address, $phone);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'تم إنشاء الحساب بنجاح'];
        } else {
            return ['success' => false, 'message' => 'فشل في إنشاء الحساب'];
        }
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT id, username, password, is_admin FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_admin'] = $user['is_admin'];
                return ['success' => true, 'message' => 'تم تسجيل الدخول بنجاح'];
            }
        }
        
        return ['success' => false, 'message' => 'اسم المستخدم أو كلمة المرور غير صحيحة'];
    }

    public function logout() {
        session_destroy();
        return ['success' => true, 'message' => 'تم تسجيل الخروج بنجاح'];
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function isAdmin() {
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
    }

    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }

        $stmt = $this->conn->prepare("SELECT id, username, email, full_name, address, phone FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
}

// إنشاء كائن المصادقة
$auth = new Auth($conn);
?> 