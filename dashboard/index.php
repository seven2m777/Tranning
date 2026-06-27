<?php
require_once "../includes/auth-check.php";
require_once "../includes/header.php";
require_once __DIR__ . "/../includes/sidebars.php";
?>

<main>
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <p>Welcome to the Library Management System.</p>
    </div>

    <div class="stats">
        <div class="card">
            <h3>Total Books</h3>
            <p>120</p>
        </div>

        <div class="card">
            <h3>Total Members</h3>
            <p>45</p>
        </div>

        <div class="card">
            <h3>Issued Books</h3>
            <p>18</p>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            Recent Activities
        </div>

        <table>
            <tr>
                <th>Date</th>
                <th>Activity</th>
            </tr>

            <tr>
                <td>2026-06-25</td>
                <td>Book "PHP Basics" issued.</td>
            </tr>

            <tr>
                <td>2026-06-24</td>
                <td>New member registered.</td>
            </tr>

            <tr>
                <td>2026-06-23</td>
                <td>Book returned.</td>
            </tr>
        </table>
    </div>
</main>

<?php require_once "../includes/footer.php"; ?>