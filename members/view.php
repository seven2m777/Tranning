<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin", "librarian"]);

require_once "../config/database.php";
require_once "../includes/header.php";
require_once "../includes/sidebars.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    $_SESSION["error"] = "Invalid member selected.";
    header("Location: index.php");
    exit;
}

$sql = "
    SELECT *
    FROM members
    WHERE id = :id
    AND is_deleted = FALSE
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    "id" => $id
]);

$member = $stmt->fetch();

if (!$member) {
    $_SESSION["error"] = "Member not found.";
    header("Location: index.php");
    exit;
}

?>

<main>
    <h1>Member Details</h1>

    <p><strong>Member Code:</strong> <?php echo htmlspecialchars($member["member_code"]); ?></p>
    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($member["full_name"]); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($member["email"] ?? ""); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($member["phone"]); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($member["address"] ?? ""); ?></p>
    <p><strong>Membership Date:</strong> <?php echo htmlspecialchars($member["membership_date"]); ?></p>
    <p><strong>Expiry Date:</strong> <?php echo htmlspecialchars($member["membership_expiry_date"] ?? ""); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($member["status"]); ?></p>
    <p><strong>Remarks:</strong> <?php echo htmlspecialchars($member["remarks"] ?? ""); ?></p>

    <a href="index.php">Back</a>
</main>

<?php require_once "../includes/footer.php"; ?>