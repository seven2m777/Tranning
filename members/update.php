<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin", "librarian"]);

require_once "../config/database.php";
require_once "../includes/helpers.php";

if (!isPostRequest()) {
    redirect("index.php");
}

$id = $_POST["id"] ?? null;
$fullName = cleanInputData($_POST["full_name"] ?? "");
$email = cleanInputData($_POST["email"] ?? "");
$phone = cleanInputData($_POST["phone"] ?? "");
$address = cleanInputData($_POST["address"] ?? "");
$membershipDate = $_POST["membership_date"] ?? "";
$membershipExpiryDate = $_POST["membership_expiry_date"] ?? null;
$status = cleanInputData($_POST["status"] ?? "active");
$remarks = cleanInputData($_POST["remarks"] ?? "");

if (!$id) {
    $_SESSION["error"] = "Invalid member selected.";
    redirect("index.php");
}

if (empty($fullName)) {
    $_SESSION["error"] = "Full name is required.";
    redirect("edit.php?id=" . $id);
}

if (empty($phone)) {
    $_SESSION["error"] = "Phone number is required.";
    redirect("edit.php?id=" . $id);
}

if (empty($membershipDate)) {
    $_SESSION["error"] = "Membership date is required.";
    redirect("edit.php?id=" . $id);
}

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["error"] = "Invalid email address.";
    redirect("edit.php?id=" . $id);
}

if (!in_array($status, ["active", "inactive"])) {
    $_SESSION["error"] = "Invalid status selected.";
    redirect("edit.php?id=" . $id);
}

if (!empty($email)) {
    $emailCheckSql = "
        SELECT id 
        FROM members 
        WHERE email = :email 
        AND id != :id
        AND is_deleted = FALSE
    ";

    $emailCheckStmt = $pdo->prepare($emailCheckSql);
    $emailCheckStmt->execute([
        "email" => $email,
        "id" => $id
    ]);

    if ($emailCheckStmt->fetch()) {
        $_SESSION["error"] = "Another member with this email already exists.";
        redirect("edit.php?id=" . $id);
    }
}

$sql = "
    UPDATE members
    SET
        full_name = :full_name,
        email = :email,
        phone = :phone,
        address = :address,
        membership_date = :membership_date,
        membership_expiry_date = :membership_expiry_date,
        status = :status,
        remarks = :remarks,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = :id
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "full_name" => $fullName,
    "email" => !empty($email) ? $email : null,
    "phone" => $phone,
    "address" => $address,
    "membership_date" => $membershipDate,
    "membership_expiry_date" => !empty($membershipExpiryDate) ? $membershipExpiryDate : null,
    "status" => $status,
    "remarks" => $remarks,
    "id" => $id
]);

$_SESSION["success"] = "Member updated successfully.";
redirect("index.php");