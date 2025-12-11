<?php
const TIMEZONE = "Asia/Manila";

/// DATABASE
// Table Names
const DEPARTMENT_TABLE = "department";
const EVENT_TABLE = "event_record";
const EVENT_TYPE_TABLE = "event_record_type";
const SYSTEM_LOG_TABLE = "system_log";
const USER_TABLE = "user";
const USER_ROLE_TABLE = "user_role";

/// IDS / PRIMARY KEYS
// User Roles
const ROLE_ADMIN = 1;

// Record Types
const AM_IN = 1;
const AM_OUT = 2;
const PM_IN = 3;
const PM_OUT = 4;