<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin", "librarian"]);

require_once "../config/database.php";
require_once "../includes/header.php";
require_once "../includes/sidebar.php";

// Active members
$membersStmt = $pdo->prepare("
    SELECT id, full_name
    FROM members
    WHERE status = 'active'
    AND is_deleted = FALSE
    ORDER BY full_name
");
$membersStmt->execute();
$members = $membersStmt->fetchAll();

// Available books
$booksStmt = $pdo->prepare("
    SELECT id, title
    FROM books
    WHERE status = 'active'
    AND is_deleted = FALSE
    AND available_copies > 0
    ORDER BY title
");
$booksStmt->execute();
$books = $booksStmt->fetchAll();

?>

<main>

<h1>Issue Book</h1>

<?php if(isset($_SESSION["error"])): ?>
    <p style="color:red;">
        <?php
        echo $_SESSION["error"];
        unset($_SESSION["error"]);
        ?>
    </p>
<?php endif; ?>

<form method="POST" action="store.php">

    <div>
        <label>Member</label>
        <select name="member_id" required>
            <option value="">Select Member</option>

            <?php foreach($members as $member): ?>

            <option value="<?= $member["id"] ?>">
                <?= htmlspecialchars($member["full_name"]) ?>
            </option>

            <?php endforeach; ?>

        </select>
    </div>

    <br>

    <div>
        <label>Book</label>
        <select name="book_id" required>
            <option value="">Select Book</option>

            <?php foreach($books as $book): ?>

            <option value="<?= $book["id"] ?>">
                <?= htmlspecialchars($book["title"]) ?>
            </option>

            <?php endforeach; ?>

        </select>
    </div>

    <br>

    <div>
        <label>Issue Date</label>
        <input type="date"
               name="issue_date"
               value="<?= date('Y-m-d') ?>"
               required>
    </div>

    <br>

    <div>
        <label>Due Date</label>
        <input type="date" name="due_date" required>
    </div>

    <br>

    <div>
        <label>Status</label>
        <select name="status">
            <option value="issued">Issued</option>
            <option value="returned">Returned</option>
            <option value="overdue">Overdue</option>
        </select>
    </div>

    <br>

    <button type="submit">Issue Book</button>

    <a href="index.php">Back</a>

</form>

</main>

<?php require_once "../includes/footer.php"; ?>