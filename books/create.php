<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin", "librarian"]);

require_once "../config/database.php";
require_once "../includes/header.php";
require_once "../includes/sidebar.php";

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
    <h1>Add Book</h1>

    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;"><?php echo $_SESSION["error"]; ?></p>
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>

    <form method="POST" action="store.php">
        <div>
            <label>Book Title</label>
            <input type="text" name="title">
        </div>

        <br>

        <div>
            <label>ISBN</label>
            <input type="text" name="isbn">
        </div>

        <br>

        <div>
            <label>Category</label>
            <select name="category_id">
                <option value="">Select Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category["id"]; ?>">
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
                    <option value="<?php echo $author["id"]; ?>">
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
                    <option value="<?php echo $publisher["id"]; ?>">
                        <?php echo htmlspecialchars($publisher["name"]); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <br>

        <div>
            <label>Edition</label>
            <input type="text" name="edition">
        </div>

        <br>

        <div>
            <label>Published Year</label>
            <input type="number" name="published_year">
        </div>

        <br>

        <div>
            <label>Total Copies</label>
            <input type="number" name="total_copies">
        </div>

        <br>

        <div>
            <label>Available Copies</label>
            <input type="number" name="available_copies">
        </div>

        <br>

        <div>
            <label>Shelf Location</label>
            <input type="text" name="shelf_location">
        </div>

        <br>

        <div>
            <label>Description</label>
            <textarea name="description"></textarea>
        </div>

        <br>

        <div>
            <label>Status</label>
            <select name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <br>

        <button type="submit">Save Book</button>
        <a href="index.php">Back</a>
    </form>
</main>

<?php require_once "../includes/footer.php"; ?>