<?php

require_once "config/database.php";

$role_id = 1;
$full_name = "System Admin";
$email = "admin@test.com";
$password = password_hash("admin123", PASSWORD_DEFAULT);

$sql = "INSERT INTO users (role_id, full_name, email, password) VALUES (:role_id, :full_name, :email, :password)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':role_id' => $role_id,
    ':full_name' => $full_name,
    ':email' => $email,
    ':password' => $password
]);

echo "Admin user created successfully!";
echo "Email: $email\n";
echo "Password: admin123\n";

?>
