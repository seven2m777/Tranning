<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin", "librarian"]);

require_once "../config/database.php";
require_once "../includes/helpers.php";

if (!isPostRequest()) {
    redirect("index.php");
}

$id = $_POST["id"] ?? null;
$memberId = $_POST["member_id"] ?? "";
$bookId = $_POST["book_id"] ?? "";
$issueDate = $_POST["issue_date"] ?? "";
$dueDate = $_POST["due_date"] ?? "";
$status = cleanInputData($_POST["status"] ?? "issued");

if (!$id) {
    $_SESSION["error"] = "Invalid issue selected.";
    redirect("index.php");
}

if (empty($memberId)) {
    $_SESSION["error"] = "Please select a member.";
    redirect("edit.php?id=".$id);
}

if (empty($bookId)) {
    $_SESSION["error"] = "Please select a book.";
    redirect("edit.php?id=".$id);
}

if (empty($issueDate)) {
    $_SESSION["error"] = "Issue date is required.";
    redirect("edit.php?id=".$id);
}

if (empty($dueDate)) {
    $_SESSION["error"] = "Due date is required.";
    redirect("edit.php?id=".$id);
}

if (strtotime($dueDate) < strtotime($issueDate)) {
    $_SESSION["error"] = "Due date cannot be before issue date.";
    redirect("edit.php?id=".$id);
}

if (!in_array($status, ["issued", "returned", "overdue"])) {
    $_SESSION["error"] = "Invalid status selected.";
    redirect("edit.php?id=".$id);
}

/*
|--------------------------------------------------------------------------
| Update issue record
|--------------------------------------------------------------------------
*/

$sql = "
UPDATE book_issues
SET
    member_id = :member_id,
    book_id = :book_id,
    issue_date = :issue_date,
    due_date = :due_date,
    status = :status,
    updated_at = CURRENT_TIMESTAMP
WHERE id = :id
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "member_id" => $memberId,
    "book_id" => $bookId,
    "issue_date" => $issueDate,
    "due_date" => $dueDate,
    "status" => $status,
    "id" => $id
]);

$_SESSION["success"] = "Book issue updated successfully.";

redirect("index.php");