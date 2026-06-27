<?php
require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin"]);

require_once "../includes/header.php";
require_once "../includes/sidebars.php";
?>

<main>
    <div class="page-header">
        <div class="page-title">
            <h1>User Management</h1>
            <p>Access authorized explicitly for administrative clearance profiles.</p>
        </div>
    </div>
</main>

<?php require_once "../includes/footer.php"; ?>