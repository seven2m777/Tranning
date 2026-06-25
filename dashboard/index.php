<?php
require_once "../includes/auth-check.php";
require_once "../includes/header.php";
require_once __DIR__ . "/../includes/sidebars.php";
?>

<style>
    body {
        margin: 0;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        background: #f6f8fa;
        color: #24292f;
    }

    main {
        padding: 24px;
    }

    .dashboard-header {
        margin-bottom: 24px;
    }

    .dashboard-header h1 {
        margin: 0;
        font-size: 28px;
    }

    .dashboard-header p {
        color: #57606a;
        margin-top: 5px;
    }

    .stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .card {
        background: #ffffff;
        border: 1px solid #d0d7de;
        border-radius: 8px;
        padding: 20px;
    }

    .card h3 {
        margin: 0;
        color: #57606a;
        font-size: 14px;
        font-weight: 600;
    }

    .card p {
        margin: 10px 0 0;
        font-size: 32px;
        font-weight: bold;
    }

    .section {
        background: #ffffff;
        border: 1px solid #d0d7de;
        border-radius: 8px;
        overflow: hidden;
    }

    .section-header {
        padding: 16px;
        border-bottom: 1px solid #d8dee4;
        font-weight: 600;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 12px 16px;
        border-bottom: 1px solid #d8dee4;
        text-align: left;
    }

    th {
        background: #f6f8fa;
        font-size: 14px;
    }

    tr:last-child td {
        border-bottom: none;
    }

    nav {
        background: #24292f;
        padding: 10px;
    }

    nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    nav li {
        margin-bottom: 6px;
    }

    nav a {
        display: block;
        color: #f0f6fc;
        text-decoration: none;
        padding: 8px 12px;
        border-radius: 6px;
    }

    nav a:hover {
        background: #30363d;
    }
</style>

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