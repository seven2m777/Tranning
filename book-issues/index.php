<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin", "librarian"]);

require_once "../config/database.php";
require_once "../includes/header.php";
require_once "../includes/sidebars.php";

$sql = "
SELECT
    bi.id,
    b.title AS book_title,
    m.full_name AS member_name,
    u.full_name AS issued_by_name,
    bi.issue_date,
    bi.due_date,
    bi.status
FROM book_issues bi
JOIN books b
    ON bi.book_id = b.id
JOIN members m
    ON bi.member_id = m.id
JOIN users u
    ON bi.issued_by = u.id
ORDER BY bi.id DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$issues = $stmt->fetchAll();

?>

<main>

    <h1>Book Issue Management</h1>

    <a href="create.php">Issue New Book</a>

    <br><br>

    <?php if (isset($_SESSION["success"])): ?>
        <p style="color:green;"><?php echo $_SESSION["success"]; ?></p>
        <?php unset($_SESSION["success"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["error"])): ?>
        <p style="color:red;"><?php echo $_SESSION["error"]; ?></p>
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>S.N.</th>
                <th>Book</th>
                <th>Member</th>
                <th>Issued By</th>
                <th>Issue Date</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        <?php if(count($issues) > 0): ?>

            <?php foreach($issues as $index => $issue): ?>

            <tr>

                <td><?php echo $index + 1; ?></td>

                <td><?php echo htmlspecialchars($issue["book_title"]); ?></td>

                <td><?php echo htmlspecialchars($issue["member_name"]); ?></td>

                <td><?php echo htmlspecialchars($issue["issued_by_name"]); ?></td>

                <td><?php echo htmlspecialchars($issue["issue_date"]); ?></td>

                <td><?php echo htmlspecialchars($issue["due_date"]); ?></td>

                <td><?php echo htmlspecialchars($issue["status"]); ?></td>

                <td>
                    <a href="view.php?id=<?php echo $issue["id"]; ?>">View</a>

                    <a href="edit.php?id=<?php echo $issue["id"]; ?>">Edit</a>

                    <a href="delete.php?id=<?php echo $issue["id"]; ?>"
                    onclick="return confirm('Are you sure you want to delete this issue?')">
                    Delete
                    </a>
                </td>

            </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>
                <td colspan="8">No book issues found.</td>
            </tr>

        <?php endif; ?>

        </tbody>

    </table>

</main>

<?php require_once "../includes/footer.php"; ?>