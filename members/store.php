<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin", "librarian"]);

require_once "../config/database.php";
require_once "../includes/helpers.php";

if (!isPostRequest()) {
    redirect("index.php");
}

$fullName = cleanInputData($_POST["full_name"] ?? "");
$email = cleanInputData($_POST["email"] ?? "");
$phone = cleanInputData($_POST["phone"] ?? "");
$address = cleanInputData($_POST["address"] ?? "");
$membershipDate = $_POST["membership_date"] ?? "";
$membershipExpiryDate = $_POST["membership_expiry_date"] ?? null;
$status = cleanInputData($_POST["status"] ?? "active");
$remarks = cleanInputData($_POST["remarks"] ?? "");

if (empty($fullName)) {
    $_SESSION["error"] = "Full name is required.";
    redirect("create.php");
}

if (empty($phone)) {
    $_SESSION["error"] = "Phone number is required.";
    redirect("create.php");
}

if (empty($membershipDate)) {
    $_SESSION["error"] = "Membership date is required.";
    redirect("create.php");
}

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["error"] = "Invalid email address.";
    redirect("create.php");
}

if (!in_array($status, ["active", "inactive"])) {
    $_SESSION["error"] = "Invalid status selected.";
    redirect("create.php");
}

if (!empty($email)) {
    $emailCheckSql = "
        SELECT id 
        FROM members 
        WHERE email = :email 
        AND is_deleted = FALSE
    ";

    $emailCheckStmt = $pdo->prepare($emailCheckSql);
    $emailCheckStmt->execute([
        "email" => $email
    ]);

    if ($emailCheckStmt->fetch()) {
        $_SESSION["error"] = "A member with this email already exists.";
        redirect("create.php");
    }
}

$lastMemberSql = "SELECT id FROM members ORDER BY id DESC LIMIT 1";
$lastMemberStmt = $pdo->prepare($lastMemberSql);
$lastMemberStmt->execute();
$lastMember = $lastMemberStmt->fetch();

$nextId = $lastMember ? $lastMember["id"] + 1 : 1;
$memberCode = "MEM-" . str_pad($nextId, 4, "0", STR_PAD_LEFT);

$sql = "
    INSERT INTO members (
        member_code,
        full_name,
        email,
        phone,
        address,
        membership_date,
        membership_expiry_date,
        status,
        remarks
    )
    VALUES (
        :member_code,
        :full_name,
        :email,
        :phone,
        :address,
        :membership_date,
        :membership_expiry_date,
        :status,
        :remarks
    )
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "member_code" => $memberCode,
    "full_name" => $fullName,
    "email" => !empty($email) ? $email : null,
    "phone" => $phone,
    "address" => $address,
    "membership_date" => $membershipDate,
    "membership_expiry_date" => !empty($membershipExpiryDate) ? $membershipExpiryDate : null,
    "status" => $status,
    "remarks" => $remarks
]);

$_SESSION["success"] = "Member created successfully.";
redirect("index.php");