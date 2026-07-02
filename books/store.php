<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["Admin", "Librarian"]);

require_once "../config/database.php";
require_once "../includes/helpers.php";

if (!isPostRequest()) {
    redirect("index.php");
}

$memberId = $_POST["member_id"] ?? "";
$bookId = $_POST["book_id"] ?? "";
$issueDate = $_POST["issue_date"] ?? "";
$dueDate = $_POST["due_date"] ?? "";
$issuedBy = $_SESSION["user_id"];

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

if ($dueDate < $issueDate) {
    $_SESSION["error"] = "Due date cannot be earlier than issue date.";
    redirect("create.php");
}

try {
    $pdo->beginTransaction();

    $memberSql = "
        SELECT id, status
        FROM members
        WHERE id = :id
        AND is_deleted = FALSE
        FOR UPDATE
    ";

    $memberStmt = $pdo->prepare($memberSql);
    $memberStmt->execute([
        "id" => $memberId
    ]);

    $member = $memberStmt->fetch();

    if (!$member) {
        throw new Exception("Member not found.");
    }

    if ($member["status"] !== "active") {
        throw new Exception("Inactive members cannot borrow books.");
    }

    $bookSql = "
        SELECT id, status, available_copies
        FROM books
        WHERE id = :id
        AND is_deleted = FALSE
        FOR UPDATE
    ";

    $bookStmt = $pdo->prepare($bookSql);
    $bookStmt->execute([
        "id" => $bookId
    ]);

    $book = $bookStmt->fetch();

    if (!$book) {
        throw new Exception("Book not found.");
    }

    if ($book["status"] !== "active") {
        throw new Exception("Inactive books cannot be issued.");
    }

    if ($book["available_copies"] <= 0) {
        throw new Exception("This book is currently unavailable.");
    }

    $issueSql = "
        INSERT INTO book_issues (
            member_id,
            book_id,
            issue_date,
            due_date,
            issued_by,
            status
        )
        VALUES (
            :member_id,
            :book_id,
            :issue_date,
            :due_date,
            :issued_by,
            'issued'
        )
    ";

    $issueStmt = $pdo->prepare($issueSql);
    $issueStmt->execute([
        "member_id" => $memberId,
        "book_id" => $bookId,
        "issue_date" => $issueDate,
        "due_date" => $dueDate,
        "issued_by" => $issuedBy
    ]);

    $updateBookSql = "
        UPDATE books
        SET
            available_copies = available_copies - 1,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = :id
    ";

    $updateBookStmt = $pdo->prepare($updateBookSql);
    $updateBookStmt->execute([
        "id" => $bookId
    ]);

    logActivity($pdo, $issuedBy, "Book Issued", "Book ID {$bookId} issued to Member ID {$memberId}.");

    $pdo->commit();

    $_SESSION["success"] = "Book issued successfully.";
    redirect("index.php");

} catch (Exception $e) {
    $pdo->rollBack();

    $_SESSION["error"] = $e->getMessage();
    redirect("create.php");
}