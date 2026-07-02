<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin", "librarian"]);

require_once "../config/database.php";
require_once "../includes/helpers.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    $_SESSION["error"] = "Invalid issue record selected.";
    redirect("index.php");
}

/* Get book_id before deleting so available copies can be restored */
$stmt = $pdo->prepare("
    SELECT book_id
    FROM book_issues
    WHERE id = :id
");
$stmt->execute([
    "id" => $id
]);

$issue = $stmt->fetch();

if (!$issue) {
    $_SESSION["error"] = "Issue record not found.";
    redirect("index.php");
}

try {

    $pdo->beginTransaction();

    // Delete issue record
    $stmt = $pdo->prepare("
        DELETE FROM book_issues
        WHERE id = :id
    ");

    $stmt->execute([
        "id" => $id
    ]);

    // Restore available copies
    $stmt = $pdo->prepare("
        UPDATE books
        SET
            available_copies = available_copies + 1,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = :book_id
    ");

    $stmt->execute([
        "book_id" => $issue["book_id"]
    ]);

    $pdo->commit();

    $_SESSION["success"] = "Issue record deleted successfully.";

} catch (Exception $e) {

    $pdo->rollBack();

    $_SESSION["error"] = "Unable to delete issue record.";
}

redirect("index.php");