<?php

require_once "includes/session.php";
require_once "config/helpers.php";

if (isset($_SESSION['user_id'])) {
    redirect('dashboard.php/index.php');
}

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';

unset($_SESSION['error'], $_SESSION['success']);

?>