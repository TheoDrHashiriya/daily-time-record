<?php
// Global Constants
require_once __DIR__ . "/config/paths.php";
require_once CONFIG_PATH . "/constants.php";

// Composer autoload
require_once VENDOR_PATH . "/autoload.php";

// Configs
require_once CONFIG_PATH . "/database.php";
require_once CONFIG_PATH . "/email.php";

// Timezone
date_default_timezone_set(TIMEZONE);

// Debugging
// ini_set("display_errors", 1);
// ini_set("display_startup_errors", 1);
// error_reporting(E_ALL);

use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

define("DB_HOST", $_ENV["DB_HOST"]);
define("DB_NAME", $_ENV["DB_NAME"]);
define("DB_USER", $_ENV["DB_USER"]);
define("DB_PASS", $_ENV["DB_PASS"]);

// Let's start!
if (session_status() === PHP_SESSION_NONE)
	session_start();