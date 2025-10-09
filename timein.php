<?php
require_once "./classes/User.php";
session_start();

if (!isset($_SESSION["user_id"])) {
	header("Location: login.php");
	exit;
}

$userObj = new User($_SESSION["user_id"]);
$userObj->timeIn();

header("Location: /");
exit;