<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin"]);

require_once "../config/database.php";
require_once "../includes/helpers.php";

if(!isPostRequest()){
    redirect("index.php");
}

$id=cleanInputData($_POST["id"] ?? "");
$name=cleanInputData($_POST["name"] ?? "");
$description=cleanInputData($_POST["description"] ?? "");
$status=cleanInputData($_POST["status"] ?? "active");

if(empty($id) || empty($name)){
    $_SESSION["error"]="Publisher name is required.";
    redirect("index.php");
}

$checkSql="SELECT id
FROM publishers
WHERE LOWER(name)=LOWER(:name)
AND id<>:id
AND is_deleted=FALSE";

$checkStmt=$pdo->prepare($checkSql);

$checkStmt->execute([
    "name"=>$name,
    "id"=>$id
]);

if($checkStmt->fetch()){
    $_SESSION["error"]="Publisher already exists.";
    redirect("edit.php?id=".$id);
}

$sql="UPDATE publishers
SET
name=:name,
description=:description,
status=:status,
updated_at=NOW()
WHERE id=:id
AND is_deleted=FALSE";

$stmt=$pdo->prepare($sql);

$stmt->execute([
    "id"=>$id,
    "name"=>$name,
    "description"=>$description,
    "status"=>$status
]);

$_SESSION["success"]="Publisher updated successfully.";

redirect("index.php");