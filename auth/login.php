<?php

require_once "../session.php";
require_once "../includes/helpers.php";

if (isset($_SESSION['user_id'])) {
    redirect('dashboard/index.php');
}

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login- LMS</title>
</head>
<body>
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>


    <?php if (!empty($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?> 

    <form method="POST" action="login_process.php">
        <div>
            <label>Email:</label>
            <input type="email" name="email" placeholder="Enter yourequired>
            </div>

            <br>

            <div>
                <label>Password:</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            