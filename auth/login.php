<?php

require_once __DIR__ . "/../session.php";
require_once __DIR__ . "/../includes/helpers.php";

if (isset($_SESSION["user_id"])) {
    redirect("../dashboard/index.php");
}

$error = $_SESSION["error"] ?? "";
$success = $_SESSION["success"] ?? "";

unset($_SESSION["error"]);
unset($_SESSION["success"]);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Library Management System</title>
</head>
<body>

    <h2>Login</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST" action="login-process.php">
        <div>
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter email">
        </div>

        <br>

        <div>
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password">
        </div>

        <br>

        <button type="submit">Login</button>
    </form>

</body>
</html>