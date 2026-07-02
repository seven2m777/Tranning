<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin", "librarian"]);

require_once "../config/database.php";
require_once "../includes/header.php";
require_once "../includes/sidebar.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    $_SESSION["error"] = "Invalid book selected.";
    header("Location: index.php");
    exit;
}

$sql = "
    SELECT 
        books.*,
        categories.name AS category_name,
        authors.name AS author_name,
        publishers.name AS publisher_name
    FROM books
    INNER JOIN categories ON categories.id = books.category_id
    INNER JOIN authors ON authors.id = books.author_id
    INNER JOIN publishers ON publishers.id = books.publisher_id
    WHERE books.id = :id AND books.is_deleted = FALSE
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    "id" => $id
]);

$book = $stmt->fetch();

if (!$book) {
    $_SESSION["error"] = "Book not found.";
    header("Location: index.php");
    exit;
}

?>

<main>
    <h1>Book Details</h1>

    <p><strong>Title:</strong> <?php echo htmlspecialchars($book["title"]); ?></p>
    <p><strong>ISBN:</strong> <?php echo htmlspecialchars($book["isbn"]); ?></p>
    <p><strong>Category:</strong> <?php echo htmlspecialchars($book["category_name"]); ?></p>
    <p><strong>Author:</strong> <?php echo htmlspecialchars($book["author_name"]); ?></p>
    <p><strong>Publisher:</strong> <?php echo htmlspecialchars($book["publisher_name"]); ?></p>
    <p><strong>Edition:</strong> <?php echo htmlspecialchars($book["edition"] ?? ""); ?></p>
    <p><strong>Published Year:</strong> <?php echo htmlspecialchars($book["published_year"] ?? ""); ?></p>
    <p><strong>Total Copies:</strong> <?php echo htmlspecialchars($book["total_copies"]); ?></p>
    <p><strong>Available Copies:</strong> <?php echo htmlspecialchars($book["available_copies"]); ?></p>
    <p><strong>Shelf Location:</strong> <?php echo htmlspecialchars($book["shelf_location"] ?? ""); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($book["status"]); ?></p>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($book["description"] ?? ""); ?></p>

    <a href="index.php">Back</a>
</main>

<?php require_once "../includes/footer.php"; ?>