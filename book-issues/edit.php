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
SELECT *
FROM book_issues
WHERE id = :id
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

/* Active Members */
$membersStmt = $pdo->prepare("
SELECT id, full_name
FROM members
WHERE status = 'active'
AND is_deleted = FALSE
ORDER BY full_name
");
$membersStmt->execute();
$members = $membersStmt->fetchAll();

/* Active Books */
$booksStmt = $pdo->prepare("
SELECT id, title
FROM books
WHERE status = 'active'
AND is_deleted = FALSE
ORDER BY title
");
$booksStmt->execute();
$books = $booksStmt->fetchAll();

?>

<main>

<h1>Edit Book Issue</h1>

<?php if (isset($_SESSION["error"])): ?>
    <p style="color:red;">
        <?php
        echo $_SESSION["error"];
        unset($_SESSION["error"]);
        ?>
    </p>
<?php endif; ?>

<form method="POST" action="update.php">

    <input type="hidden"
           name="id"
           value="<?php echo $issue["id"]; ?>">

    <div>
        <label>Member</label>

        <select name="member_id">

            <option value="">Select Member</option>

            <?php foreach ($members as $member): ?>

            <option value="<?php echo $member["id"]; ?>"
                <?php echo ($issue["member_id"] == $member["id"]) ? "selected" : ""; ?>>

                <?php echo htmlspecialchars($member["full_name"]); ?>

            </option>

            <?php endforeach; ?>

        </select>

    </div>

    <br>

    <div>

        <label>Book</label>

        <select name="book_id">

            <option value="">Select Book</option>

            <?php foreach ($books as $book): ?>

            <option value="<?php echo $book["id"]; ?>"
                <?php echo ($issue["book_id"] == $book["id"]) ? "selected" : ""; ?>>

                <?php echo htmlspecialchars($book["title"]); ?>

            </option>

            <?php endforeach; ?>

        </select>

    </div>

    <br>

    <div>

        <label>Issue Date</label>

        <input
            type="date"
            name="issue_date"
            value="<?php echo $issue["issue_date"]; ?>">

    </div>

    <br>

    <div>

        <label>Due Date</label>

        <input
            type="date"
            name="due_date"
            value="<?php echo $issue["due_date"]; ?>">

    </div>

    <br>

    <div>

        <label>Status</label>

        <select name="status">

            <option value="issued"
                <?php echo ($issue["status"] == "issued") ? "selected" : ""; ?>>
                Issued
            </option>

            <option value="returned"
                <?php echo ($issue["status"] == "returned") ? "selected" : ""; ?>>
                Returned
            </option>

            <option value="overdue"
                <?php echo ($issue["status"] == "overdue") ? "selected" : ""; ?>>
                Overdue
            </option>

        </select>

    </div>

    <br>

    <button type="submit">Update Issue</button>

    <a href="index.php">Back</a>

</form>

</main>

<?php require_once "../includes/footer.php"; ?>