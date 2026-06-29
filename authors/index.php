<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin"]);

require_once "../config/database.php";
require_once "../includes/header.php";
require_once "../includes/sidebars.php";

$sql = "SELECT * FROM authors WHERE is_deleted = FALSE ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$authors = $stmt->fetchAll();

?>

<main>
    <h1>Author Management</h1>

    <a href="create.php">Add New Author</a>

    <br><br>

    <?php if (isset($_SESSION["success"])): ?>
        <p style="color: green;">
            <?php
            echo $_SESSION["success"];
            unset($_SESSION["success"]);
            ?>
        </p>
    <?php endif; ?>

    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;">
            <?php
            echo $_SESSION["error"];
            unset($_SESSION["error"]);
            ?>
        </p>
    <?php endif; ?>

    <table cellpadding="10" cellspacing="0" style="border:1px solid #ccc; width:100%;">
        <thead style="background:#f2f2f2;">
            <tr>
                <th>S.N.</th>
                <th>Name</th>
                <th>Bio</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        <?php if (count($authors) > 0): ?>

            <?php foreach ($authors as $index => $author): ?>

                <tr>
                    <td><?= $index + 1 ?></td>

                    <td><?= htmlspecialchars($author["name"]) ?></td>

                    <td><?= htmlspecialchars($author["bio"] ?? "") ?></td>

                    <td><?= htmlspecialchars($author["status"]) ?></td>

                    <td><?= htmlspecialchars($author["created_at"]) ?></td>

                    <td>
                        <a href="edit.php?id=<?= $author["id"] ?>">Edit</a>

                        |

                        <a href="delete.php?id=<?= $author["id"] ?>"
                           onclick="return confirm('Are you sure you want to delete this author?')">
                            Delete
                        </a>
                    </td>
                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="6">No authors found.</td>
            </tr>

        <?php endif; ?>

        </tbody>
    </table>

</main>

<?php require_once "../includes/footer.php"; ?>