<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin"]);

require_once "../includes/header.php";
require_once "../includes/sidebars.php";

?>

<main>
    <h1>Add Author</h1>

    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;">
            <?php
            echo $_SESSION["error"];
            unset($_SESSION["error"]);
            ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="store.php">

        <div>
            <label>Author Name</label><br>
            <input type="text" name="name" required>
        </div>

        <br>

        <div>
            <label>Biography</label><br>
            <textarea name="bio" rows="5"></textarea>
        </div>

        <br>

        <div>
            <label>Status</label><br>
            <select name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <br>

        <button type="submit">Save Author</button>
        <a href="index.php">Back</a>

    </form>
</main>

<?php require_once "../includes/footer.php"; ?>