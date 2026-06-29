<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin"]);

require_once "../config/database.php";
require_once "../includes/helpers.php";
require_once "../includes/header.php";
require_once "../includes/sidebars.php";

$id = $_GET["id"] ?? null;

if (!$id) {
    $_SESSION["error"] = "Invalid author selected.";
    redirect("index.php");
}

$sql = "SELECT * FROM authors WHERE id = :id AND is_deleted = FALSE";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    "id" => $id
]);

$author = $stmt->fetch();

if (!$author) {
    $_SESSION["error"] = "Author not found.";
    redirect("index.php");
}

?>

<main>
    <h1>Edit Author</h1>

    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;">
            <?php
            echo $_SESSION["error"];
            unset($_SESSION["error"]);
            ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="update.php">

        <input type="hidden" name="id" value="<?php echo $author["id"]; ?>">

        <div>
            <label>Author Name</label>
            <input
                type="text"
                name="name"
                value="<?php echo htmlspecialchars($author["name"]); ?>"
                required
            >
        </div>

        <br>

        <div>
            <label>Biography</label>
            <textarea name="bio"><?php echo htmlspecialchars($author["bio"] ?? ""); ?></textarea>
        </div>

        <br>

        <div>
            <label>Status</label>

            <select name="status">
                <option value="active" <?php echo $author["status"] == "active" ? "selected" : ""; ?>>
                    Active
                </option>

                <option value="inactive" <?php echo $author["status"] == "inactive" ? "selected" : ""; ?>>
                    Inactive
                </option>
            </select>
        </div>

        <br>

        <button type="submit">Update Author</button>
        <a href="index.php">Back</a>

    </form>

</main>

<?php require_once "../includes/footer.php"; ?>