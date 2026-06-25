<?php

require_once __DIR__ . "/../session.php";
require_once __DIR__ . "/helpers.php";

if (!isset($_SESSION["user_id"])) {
    $_SESSION["error"] = "Please login first.";
    redirect("../auth/login.php");
}

