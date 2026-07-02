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

$bookSql = "SELECT * FROM books WHERE id = :id AND is_deleted = FALSE";
$bookStmt = $pdo->prepare($bookSql);
$bookStmt->execute([
    "id" => $id
]);

$book = $bookStmt->fetch();

if (!$book) {
    $_SESSION["error"] = "Book not found.";
    header("Location: index.php");
    exit;
}

$categoriesStmt = $pdo->prepare("SELECT id, name FROM categories WHERE status = 'active' AND is_deleted = FALSE ORDER BY name ASC");
$categoriesStmt->execute();
$categories = $categoriesStmt->fetchAll();

$authorsStmt = $pdo->prepare("SELECT id, name FROM authors WHERE status = 'active' AND is_deleted = FALSE ORDER BY name ASC");
$authorsStmt->execute();
$authors = $authorsStmt->fetchAll();

$publishersStmt = $pdo->prepare("SELECT id, name FROM publishers WHERE status = 'active' AND is_deleted = FALSE ORDER BY name ASC");
$publishersStmt->execute();
$publishers = $publishersStmt->fetchAll();

?>

<main>
    <h1>Edit Book</h1>

    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;"><?php echo $_SESSION["error"]; ?></p>
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>

    <form method="POST" action="update.php">
        <input type="hidden" name="id" value="<?php echo $book["id"]; ?>">

        <div>
            <label>Book Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($book["title"]); ?>">
        </div>

        <br>

        <div>
            <label>ISBN</label>
            <input type="text" name="isbn" value="<?php echo htmlspecialchars($book["isbn"]); ?>">
        </div>

        <br>

        <div>
            <label>Category</label>
            <select name="category_id">
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category["id"]; ?>"
                        <?php echo $book["category_id"] == $category["id"] ? "selected" : ""; ?>>
                        <?php echo htmlspecialchars($category["name"]); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <br>

        <div>
            <label>Author</label>
            <select name="author_id">
                <option value="">Select Author</option>
                <?php foreach ($authors as $author): ?>
                    <option value="<?php echo $author["id"]; ?>"
                        <?php echo $book["author_id"] == $author["id"] ? "selected" : ""; ?>>
                        <?php echo htmlspecialchars($author["name"]); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <br>

        <div>
            <label>Publisher</label>
            <select name="publisher_id">
                <option value="">Select Publisher</option>
                <?php foreach ($publishers as $publisher): ?>
                    <option value="<?php echo $publisher["id"]; ?>"
                        <?php echo $book["publisher_id"] == $publisher["id"] ? "selected" : ""; ?>>
                        <?php echo htmlspecialchars($publisher["name"]); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <br>

        <div>
            <label>Edition</label>
            <input type="text" name="edition" value="<?php echo htmlspecialchars($book["edition"] ?? ""); ?>">
        </div>

        <br>

        <div>
            <label>Published Year</label>
            <input type="number" name="published_year" value="<?php echo htmlspecialchars($book["published_year"] ?? ""); ?>">
        </div>

        <br>

        <div>
            <label>Total Copies</label>
            <input type="number" name="total_copies" value="<?php echo htmlspecialchars($book["total_copies"]); ?>">
        </div>

        <br>

        <div>
            <label>Available Copies</label>
            <input type="number" name="available_copies" value="<?php echo htmlspecialchars($book["available_copies"]); ?>">
        </div>

        <br>

        <div>
            <label>Shelf Location</label>
            <input type="text" name="shelf_location" value="<?php echo htmlspecialchars($book["shelf_location"] ?? ""); ?>">
        </div>

        <br>

        <div>
            <label>Description</label>
            <textarea name="description"><?php echo htmlspecialchars($book["description"] ?? ""); ?></textarea>
        </div>

        <br>

        <div>
            <label>Status</label>
            <select name="status">
                <option value="active" <?php echo $book["status"] === "active" ? "selected" : ""; ?>>
                    Active
                </option>
                <option value="inactive" <?php echo $book["status"] === "inactive" ? "selected" : ""; ?>>
                    Inactive
                </option>
            </select>
        </div>

        <br>

        <button type="submit">Update Book</button>
        <a href="index.php">Back</a>
    </form>
</main>

<?php require_once "../includes/footer.php"; ?>