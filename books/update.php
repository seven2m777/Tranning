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
$title = cleanInputData($_POST["title"] ?? "");
$isbn = cleanInputData($_POST["isbn"] ?? "");
$categoryId = $_POST["category_id"] ?? "";
$authorId = $_POST["author_id"] ?? "";
$publisherId = $_POST["publisher_id"] ?? "";
$edition = cleanInputData($_POST["edition"] ?? "");
$publishedYear = $_POST["published_year"] ?? null;
$totalCopies = $_POST["total_copies"] ?? 0;
$availableCopies = $_POST["available_copies"] ?? 0;
$shelfLocation = cleanInputData($_POST["shelf_location"] ?? "");
$description = cleanInputData($_POST["description"] ?? "");
$status = cleanInputData($_POST["status"] ?? "active");

if (!$id) {
    $_SESSION["error"] = "Invalid book selected.";
    redirect("index.php");
}

if (empty($title)) {
    $_SESSION["error"] = "Book title is required.";
    redirect("edit.php?id=" . $id);
}

if (empty($isbn)) {
    $_SESSION["error"] = "ISBN is required.";
    redirect("edit.php?id=" . $id);
}

if (empty($categoryId) || empty($authorId) || empty($publisherId)) {
    $_SESSION["error"] = "Category, author, and publisher are required.";
    redirect("edit.php?id=" . $id);
}

if ($totalCopies <= 0) {
    $_SESSION["error"] = "Total copies must be greater than zero.";
    redirect("edit.php?id=" . $id);
}

if ($availableCopies < 0) {
    $_SESSION["error"] = "Available copies cannot be negative.";
    redirect("edit.php?id=" . $id);
}

if ($availableCopies > $totalCopies) {
    $_SESSION["error"] = "Available copies cannot be greater than total copies.";
    redirect("edit.php?id=" . $id);
}

if (!in_array($status, ["active", "inactive"])) {
    $_SESSION["error"] = "Invalid status selected.";
    redirect("edit.php?id=" . $id);
}

$checkSql = "
    SELECT id 
    FROM books 
    WHERE isbn = :isbn 
    AND id != :id 
    AND is_deleted = FALSE
";

$checkStmt = $pdo->prepare($checkSql);
$checkStmt->execute([
    "isbn" => $isbn,
    "id" => $id
]);

if ($checkStmt->fetch()) {
    $_SESSION["error"] = "Another book with this ISBN already exists.";
    redirect("edit.php?id=" . $id);
}

$sql = "
    UPDATE books
    SET
        category_id = :category_id,
        author_id = :author_id,
        publisher_id = :publisher_id,
        title = :title,
        isbn = :isbn,
        edition = :edition,
        published_year = :published_year,
        total_copies = :total_copies,
        available_copies = :available_copies,
        shelf_location = :shelf_location,
        description = :description,
        status = :status,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = :id
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "category_id" => $categoryId,
    "author_id" => $authorId,
    "publisher_id" => $publisherId,
    "title" => $title,
    "isbn" => $isbn,
    "edition" => $edition,
    "published_year" => !empty($publishedYear) ? $publishedYear : null,
    "total_copies" => $totalCopies,
    "available_copies" => $availableCopies,
    "shelf_location" => $shelfLocation,
    "description" => $description,
    "status" => $status,
    "id" => $id
]);

$_SESSION["success"] = "Book updated successfully.";
redirect("index.php");