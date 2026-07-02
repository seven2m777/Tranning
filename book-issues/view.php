<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin", "librarian"]);

require_once "../config/database.php";
require_once "../includes/header.php";
require_once "../includes/sidebar.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    $_SESSION["error"] = "Invalid issue selected.";
    header("Location: index.php");
    exit;
}

$sql = "
SELECT
    bi.*,
    b.title AS book_title,
    b.isbn,
    m.member_code,
    m.full_name AS member_name,
    u.full_name AS issued_by_name
FROM book_issues bi
INNER JOIN books b
    ON bi.book_id = b.id
INNER JOIN members m
    ON bi.member_id = m.id
INNER JOIN users u
    ON bi.issued_by = u.id
WHERE bi.id = :id
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    "id" => $id
]);

$issue = $stmt->fetch();

if (!$issue) {
    $_SESSION["error"] = "Issue record not found.";
    header("Location: index.php");
    exit;
}

?>

<main>

<h1>Book Issue Details</h1>

<p><strong>Book:</strong> <?php echo htmlspecialchars($issue["book_title"]); ?></p>

<p><strong>ISBN:</strong> <?php echo htmlspecialchars($issue["isbn"]); ?></p>

<p><strong>Member Code:</strong> <?php echo htmlspecialchars($issue["member_code"]); ?></p>

<p><strong>Member Name:</strong> <?php echo htmlspecialchars($issue["member_name"]); ?></p>

<p><strong>Issued By:</strong> <?php echo htmlspecialchars($issue["issued_by_name"]); ?></p>

<p><strong>Issue Date:</strong> <?php echo htmlspecialchars($issue["issue_date"]); ?></p>

<p><strong>Due Date:</strong> <?php echo htmlspecialchars($issue["due_date"]); ?></p>

<p><strong>Status:</strong> <?php echo htmlspecialchars($issue["status"]); ?></p>

<p><strong>Created At:</strong> <?php echo htmlspecialchars($issue["created_at"]); ?></p>

<p><strong>Updated At:</strong> <?php echo htmlspecialchars($issue["updated_at"]); ?></p>

<br>

<a href="index.php">Back</a>

</main>

<?php require_once "../includes/footer.php"; ?>