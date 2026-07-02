<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin"]);

require_once "../includes/header.php";
require_once "../includes/sidebars.php";

?>

<main>

<h1>Add Publisher</h1>

<?php if(isset($_SESSION["error"])): ?>
<p style="color:red;"><?php echo $_SESSION["error"]; ?></p>
<?php unset($_SESSION["error"]); ?>
<?php endif; ?>

<form method="POST" action="store.php">

<label>Publisher Name</label>
<input type="text" name="name">

<br><br>

<label>Description</label>
<textarea name="description"></textarea>

<br><br>

<label>Status</label>

<select name="status">
    <option value="active">Active</option>
    <option value="inactive">Inactive</option>
</select>

<br><br>

<button type="submit">Save Publisher</button>

<a href="index.php">Back</a>

</form>

</main>

<?php require_once "../includes/footer.php"; ?>