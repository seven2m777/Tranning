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
$description = cleanInputData($_POST["description"] ?? "");
$status = cleanInputData($_POST["status"] ?? "active");

if (empty($name)) {
    $_SESSION["error"] = "Publisher name is required.";
    redirect("create.php");
}

if (!in_array($status, ["active", "inactive"])) {
    $_SESSION["error"] = "Invalid status.";
    redirect("create.php");
}

$checkSql = "SELECT id FROM publishers
WHERE LOWER(name)=LOWER(:name)
AND is_deleted=FALSE";

$checkStmt = $pdo->prepare($checkSql);

$checkStmt->execute([
    "name"=>$name
]);

if($checkStmt->fetch()){
    $_SESSION["error"]="Publisher already exists.";
    redirect("create.php");
}

$sql="INSERT INTO publishers(name,description,status)
VALUES(:name,:description,:status)";

$stmt=$pdo->prepare($sql);

$stmt->execute([
    "name"=>$name,
    "description"=>$description,
    "status"=>$status
]);

$_SESSION["success"]="Publisher created successfully.";

redirect("index.php");