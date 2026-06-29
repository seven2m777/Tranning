<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin"]);

require_once "../config/database.php";
require_once "../includes/helpers.php";

if (!isPostRequest()) {
    redirect("index.php");
}

$name = cleanInputData($_POST["name"] ?? "");
$bio = cleanInputData($_POST["bio"] ?? "");
$status = cleanInputData($_POST["status"] ?? "active");

if (empty($name)) {
    $_SESSION["error"] = "Author name is required.";
    redirect("create.php");
}

if (!in_array($status, ["active", "inactive"])) {
    $_SESSION["error"] = "Invalid status selected.";
    redirect("create.php");
}

$checkSql = "
    SELECT id
    FROM authors
    WHERE LOWER(name) = LOWER(:name)
    AND is_deleted = FALSE
";

$checkStmt = $pdo->prepare($checkSql);
$checkStmt->execute([
    "name" => $name
]);

$existingAuthor = $checkStmt->fetch();

if ($existingAuthor) {
    $_SESSION["error"] = "Author already exists.";
    redirect("create.php");
}

$sql = "
    INSERT INTO authors (name, bio, status)
    VALUES (:name, :bio, :status)
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "name" => $name,
    "bio" => $bio,
    "status" => $status
]);

$_SESSION["success"] = "Author created successfully.";

redirect("index.php");