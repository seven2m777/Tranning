<?php

require_once "config/database.php";

$role_id = 1;
$full_name = "System Admin";
$email = "admin@test.com";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$phone = "9800000000";

// Check if admin already exists
$check = $pdo->prepare("SELECT id FROM users WHERE email = :email");
$check->execute([':email' => $email]);

if ($check->fetch()) {
    die("Admin user already exists!");
}

$sql = "INSERT INTO users (role_id, full_name, email, password, phone) VALUES (:role_id, :full_name, :email, :password, :phone)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':role_id' => $role_id,
    ':full_name' => $full_name,
    ':email' => $email,
    ':password' => $password,
    ':phone' => $phone
]);

echo "Admin user created successfully!";
?>