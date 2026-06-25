<?php

require_once "../includes/session.php";
require_once "../includes/helpers.php";

$_SESSION = [];

session_destroy();

redirect("login.php");
