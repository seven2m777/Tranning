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
    <h1>Edit Member</h1>

    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;"><?php echo $_SESSION["error"]; ?></p>
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>

    <form method="POST" action="update.php">
        <input type="hidden" name="id" value="<?php echo $member["id"]; ?>">

        <div>
            <label>Member Code</label>
            <input type="text" value="<?php echo htmlspecialchars($member["member_code"]); ?>" disabled>
        </div>

        <br>

        <div>
            <label>Full Name</label>
            <input type="text" name="full_name" value="<?php echo htmlspecialchars($member["full_name"]); ?>">
        </div>

        <br>

        <div>
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($member["email"] ?? ""); ?>">
        </div>

        <br>

        <div>
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($member["phone"]); ?>">
        </div>

        <br>

        <div>
            <label>Address</label>
            <textarea name="address"><?php echo htmlspecialchars($member["address"] ?? ""); ?></textarea>
        </div>

        <br>

        <div>
            <label>Membership Date</label>
            <input type="date" name="membership_date" value="<?php echo htmlspecialchars($member["membership_date"]); ?>">
        </div>

        <br>

        <div>
            <label>Membership Expiry Date</label>
            <input type="date" name="membership_expiry_date" value="<?php echo htmlspecialchars($member["membership_expiry_date"] ?? ""); ?>">
        </div>

        <br>

        <div>
            <label>Status</label>
            <select name="status">
                <option value="active" <?php echo $member["status"] === "active" ? "selected" : ""; ?>>
                    Active
                </option>
                <option value="inactive" <?php echo $member["status"] === "inactive" ? "selected" : ""; ?>>
                    Inactive
                </option>
            </select>
        </div>

        <br>

        <div>
            <label>Remarks</label>
            <textarea name="remarks"><?php echo htmlspecialchars($member["remarks"] ?? ""); ?></textarea>
        </div>

        <br>

        <button type="submit">Update Member</button>
        <a href="index.php">Back</a>
    </form>
</main>

<?php require_once "../includes/footer.php"; ?>