<?php

require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin", "librarian"]);

require_once "../includes/header.php";
require_once "../includes/sidebars.php";

?>

<main>
    <h1>Add Member</h1>

    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;"><?php echo $_SESSION["error"]; ?></p>
        <?php unset($_SESSION["error"]); ?>
    <?php endif; ?>

    <form method="POST" action="store.php">
        <div>
            <label>Full Name</label>
            <input type="text" name="full_name">
        </div>

        <br>

        <div>
            <label>Email</label>
            <input type="email" name="email">
        </div>

        <br>

        <div>
            <label>Phone</label>
            <input type="text" name="phone">
        </div>

        <br>

        <div>
            <label>Address</label>
            <textarea name="address"></textarea>
        </div>

        <br>

        <div>
            <label>Membership Date</label>
            <input type="date" name="membership_date" value="<?php echo date('Y-m-d'); ?>">
        </div>

        <br>

        <div>
            <label>Membership Expiry Date</label>
            <input type="date" name="membership_expiry_date">
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

        <div>
            <label>Remarks</label>
            <textarea name="remarks"></textarea>
        </div>

        <br>

        <button type="submit">Save Member</button>
        <a href="index.php">Back</a>
    </form>
</main>

<?php require_once "../includes/footer.php"; ?>