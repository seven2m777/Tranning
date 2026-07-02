<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin", "librarian"]);

require_once "../config/database.php";
require_once "../includes/helpers.php";

if (!isPostRequest()) {
    redirect("index.php");
}

$memberId = $_POST["member_id"] ?? "";
$bookId = $_POST["book_id"] ?? "";
$issueDate = $_POST["issue_date"] ?? "";
$dueDate = $_POST["due_date"] ?? "";
$status = cleanInputData($_POST["status"] ?? "issued");

/*
|--------------------------------------------------------------------------
| Logged in user
|--------------------------------------------------------------------------
| Change this if your session variable has a different name.
*/

$issuedBy = $_SESSION["user_id"];

/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

if (empty($memberId)) {
    $_SESSION["error"] = "Please select a member.";
    redirect("create.php");
}

if (empty($bookId)) {
    $_SESSION["error"] = "Please select a book.";
    redirect("create.php");
}

if (empty($issueDate)) {
    $_SESSION["error"] = "Issue date is required.";
    redirect("create.php");
}

if (empty($dueDate)) {
    $_SESSION["error"] = "Due date is required.";
    redirect("create.php");
}

if (strtotime($dueDate) < strtotime($issueDate)) {
    $_SESSION["error"] = "Due date cannot be before issue date.";
    redirect("create.php");
}

if (!in_array($status, ["issued", "returned", "overdue"])) {
    $_SESSION["error"] = "Invalid status selected.";
    redirect("create.php");
}

/*
|--------------------------------------------------------------------------
| Check Member
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT id
    FROM members
    WHERE id = :id
      AND status = 'active'
      AND is_deleted = FALSE
");

$stmt->execute([
    "id" => $memberId
]);

if (!$stmt->fetch()) {
    $_SESSION["error"] = "Selected member does not exist.";
    redirect("create.php");
}

/*
|--------------------------------------------------------------------------
| Check Book
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT id, available_copies
    FROM books
    WHERE id = :id
      AND status = 'active'
      AND is_deleted = FALSE
");

$stmt->execute([
    "id" => $bookId
]);

$book = $stmt->fetch();

if (!$book) {
    $_SESSION["error"] = "Selected book does not exist.";
    redirect("create.php");
}

if ($book["available_copies"] <= 0) {
    $_SESSION["error"] = "No copies of this book are available.";
    redirect("create.php");
}

/*
|--------------------------------------------------------------------------
| Prevent duplicate issue
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT id
    FROM book_issues
    WHERE member_id = :member_id
      AND book_id = :book_id
      AND status = 'issued'
");

$stmt->execute([
    "member_id" => $memberId,
    "book_id" => $bookId
]);

if ($stmt->fetch()) {
    $_SESSION["error"] = "This member has already issued this book.";
    redirect("create.php");
}

/*
|--------------------------------------------------------------------------
| Transaction
|--------------------------------------------------------------------------
*/

try {

    $pdo->beginTransaction();

    $sql = "
        INSERT INTO book_issues
        (
            member_id,
            book_id,
            issued_by,
            issue_date,
            due_date,
            status
        )
        VALUES
        (
            :member_id,
            :book_id,
            :issued_by,
            :issue_date,
            :due_date,
            :status
        )
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "member_id" => $memberId,
        "book_id" => $bookId,
        "issued_by" => $issuedBy,
        "issue_date" => $issueDate,
        "due_date" => $dueDate,
        "status" => $status
    ]);

    /*
    |------------------------------------------------------------
    | Reduce available copies
    |------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        UPDATE books
        SET
            available_copies = available_copies - 1,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = :id
    ");

    $stmt->execute([
        "id" => $bookId
    ]);

    $pdo->commit();

    $_SESSION["success"] = "Book issued successfully.";

} catch (Exception $e) {

    $pdo->rollBack();

    $_SESSION["error"] = "Unable to issue book.";
}

redirect("index.php");