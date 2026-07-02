<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin"]);

require_once "../config/database.php";
require_once "../includes/header.php";
require_once "../includes/sidebars.php";

$sql = "SELECT * FROM publishers WHERE is_deleted = FALSE ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$publishers = $stmt->fetchAll();

?>

<main>
    <h1>Publisher Management</h1>

    <a href="create.php">Add New Publisher</a>

    <br><br>

    <?php if (isset($_SESSION["success"])): ?>
        <p style="color:green;"><?php echo $_SESSION["success"]; ?></p>
        <?php unset($_SESSION["success"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["error"])): ?>
        <p style="color:red;"><?php echo $_SESSION["error"]; ?></p>
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>

    <table cellpadding="10" cellspacing="0" style="width:100%; border:1px solid #ccc;">
        <thead style="background:#f2f2f2;">
            <tr>
                <th>S.N.</th>
                <th>Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        <?php if(count($publishers)>0): ?>

            <?php foreach($publishers as $index=>$publisher): ?>

            <tr>
                <td><?= $index+1 ?></td>
                <td><?= htmlspecialchars($publisher["name"]) ?></td>
                <td><?= htmlspecialchars($publisher["description"] ?? "") ?></td>
                <td><?= htmlspecialchars($publisher["status"]) ?></td>
                <td><?= htmlspecialchars($publisher["created_at"]) ?></td>
                <td>
                    <a href="edit.php?id=<?= $publisher["id"] ?>">Edit</a> |
                    <a href="delete.php?id=<?= $publisher["id"] ?>" onclick="return confirm('Delete this publisher?')">Delete</a>
                </td>
            </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="6">No publishers found.</td>
            </tr>

        <?php endif; ?>

        </tbody>
    </table>
</main>

<?php require_once "../includes/footer.php"; ?>