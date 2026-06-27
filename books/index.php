<?php
require_once "../includes/auth-check.php";
require_once "../includes/role-check.php";

allowRoles(["admin", "librarian"]);

require_once "../includes/header.php";
require_once "../includes/sidebars.php";
?>

<main>
    <div class="page-header">
        <div class="page-title">
            <h1>📚 Book Management</h1>
            <p>Manage all books in your library.</p>
        </div>
        <a href="#" class="add-btn">+ Add New Asset</a>
    </div>

    <div class="summary">
        <div class="summary-card">
            <h3>Total Books</h3>
            <p>120</p>
        </div>
        <div class="summary-card">
            <h3>Available</h3>
            <p>102</p>
        </div>
        <div class="summary-card">
            <h3>Issued</h3>
            <p>18</p>
        </div>
    </div>

    <div class="table-card">
        <div class="table-header">
            <h2>Book Registry Database</h2>
            <div class="search-box">
                <input type="text" placeholder="Search parameters...">
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>PHP Basics</td>
                    <td>John Smith</td>
                    <td>Programming</td>
                    <td><span class="badge available">Available</span></td>
                    <td>
                        <a href="#" class="action edit">Edit</a>
                        <a href="#" class="action delete">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Database Systems</td>
                    <td>Thomas Connolly</td>
                    <td>Database</td>
                    <td><span class="badge issued">Issued</span></td>
                    <td>
                        <a href="#" class="action edit">Edit</a>
                        <a href="#" class="action delete">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Clean Code</td>
                    <td>Robert C. Martin</td>
                    <td>Software</td>
                    <td><span class="badge available">Available</span></td>
                    <td>
                        <a href="#" class="action edit">Edit</a>
                        <a href="#" class="action delete">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Java Programming</td>
                    <td>James Gosling</td>
                    <td>Programming</td>
                    <td><span class="badge available">Available</span></td>
                    <td>
                        <a href="#" class="action edit">Edit</a>
                        <a href="#" class="action delete">Delete</a>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Operating Systems</td>
                    <td>Silberschatz</td>
                    <td>Computer Science</td>
                    <td><span class="badge issued">Issued</span></td>
                    <td>
                        <a href="#" class="action edit">Edit</a>
                        <a href="#" class="action delete">Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</main>

<?php require_once "../includes/footer.php"; ?>