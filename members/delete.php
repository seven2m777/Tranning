<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin", "librarian"]);

require_once "../config/database.php";
require_once "../includes/helpers.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    $_SESSION["error"] = "Invalid member selected.";
    redirect("index.php");
}

$sql = "
    UPDATE members
    SET
        is_deleted = TRUE,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = :id
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    "id" => $id
]);

$_SESSION["success"] = "Member deleted successfully.";
redirect("index.php");