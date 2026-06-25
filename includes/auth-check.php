<?php

require_once "session.php";
require_once "helpers.php";

if (!isset($_SESSION["user_id"])) {
    $_SESSION["error"] = "Please login first.";
    redirect("../auth/login.php");
}