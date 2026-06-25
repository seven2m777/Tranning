<?php

require_once __DIR__ . "/../session.php";
require_once "../includes/helpers.php";

$_SESSION = [];

session_destroy();

redirect("login.php");
