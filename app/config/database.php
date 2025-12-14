<?php
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable("../../");
$dotenv->load();

define("DB_HOST", $_ENV["DB_HOST"]);
define("DB_NAME", $_ENV["DB_NAME"]);
define("DB_USER", $_ENV["DB_USER"]);
define("DB_PASS", $_ENV["DB_PASS"]);

// Table Names
const DBT_DEPARTMENT = "department";
const DBT_EVENT = "event_record";
const DBT_EVENT_TYPE = "event_record_type";
const DBT_NOTIFICATION_READ = "notification_read";
const DBT_SYSTEM_LOG = "system_log";
const DBT_SYSTEM_LOG_TYPE = "system_log_type";
const DBT_USER = "user";
const DBT_USER_ROLE = "user_role";