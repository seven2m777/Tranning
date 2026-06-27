<?php

require_once __DIR__ . "/../session.php";
require_once __DIR__ . "/helpers.php";

function allowRoles(array $allowedRoles)
{
    if (!isset($_SESSION["role_name"])) {
        $_SESSION["error"] = "Please login first.";
        redirect("../auth/login.php");
    }

    if (!in_array($_SESSION["role_name"], $allowedRoles)) {
        $_SESSION["error"] = "You are not allowed to access this page.";
        redirect("../dashboard/index.php");
    }
}