<?php

require_once "config/database.php";

$role_id = 2;
$full_name = "Library Admin";
$email = "librarian@example.com";
$password = password_hash("librarian123", PASSWORD_DEFAULT);

$check = $pdo->prepare("SELECT id FROM users WHERE email = :email");
$check->execute([':email' => $email]);

if ($check->fetch()) {
    die("Librarian user already exists!");
}


$sql = "INSERT INTO users (role_id, full_name, email, password) VALUES (:role_id, :full_name, :email, :password)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':role_id' => $role_id,
    ':full_name' => $full_name,
    ':email' => $email,
    ':password' => $password
]);

echo "Librarian user created successfully!";
?>