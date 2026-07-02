<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin", "librarian"]);

require_once "../config/database.php";
require_once "../includes/header.php";
require_once "../includes/sidebars.php";

$sql = "
    SELECT *
    FROM members
    WHERE is_deleted = FALSE
    ORDER BY id DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$members = $stmt->fetchAll();

?>

<main>
    <h1>Member Management</h1>

    <a href="create.php">Add New Member</a>

    <br><br>

    <?php if (isset($_SESSION["success"])): ?>
        <p style="color: green;"><?php echo $_SESSION["success"]; ?></p>
        <?php unset($_SESSION["success"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;"><?php echo $_SESSION["error"]; ?></p>
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>S.N.</th>
                <th>Member Code</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Membership Date</th>
                <th>Expiry Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            <?php if (count($members) > 0): ?>
                <?php foreach ($members as $index => $member): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($member["member_code"]); ?></td>
                        <td><?php echo htmlspecialchars($member["full_name"]); ?></td>
                        <td><?php echo htmlspecialchars($member["email"] ?? ""); ?></td>
                        <td><?php echo htmlspecialchars($member["phone"]); ?></td>
                        <td><?php echo htmlspecialchars($member["membership_date"]); ?></td>
                        <td><?php echo htmlspecialchars($member["membership_expiry_date"] ?? ""); ?></td>
                        <td><?php echo htmlspecialchars($member["status"]); ?></td>
                        <td>
                            <a href="view.php?id=<?php echo $member["id"]; ?>">View</a>
                            <a href="edit.php?id=<?php echo $member["id"]; ?>">Edit</a>
                            <a href="delete.php?id=<?php echo $member["id"]; ?>"
                               onclick="return confirm('Are you sure you want to delete this member?')">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No members found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php require_once "../includes/footer.php"; ?>