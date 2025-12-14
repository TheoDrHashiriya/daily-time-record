<?php
const TIMEZONE = "Asia/Manila";

/// TIME METRICS
const EARLY_IN_OFFSET = -60 * 30;
const EARLY_OUT_OFFSET = -60 * 15;
const LATE_GRACE_PERIOD = 60 * 15;

/// IDS / PRIMARY KEYS
// System Log Types
const LOG_AM_IN_EARLY = 1;
const LOG_AM_IN = 2;
const LOG_AM_IN_LATE = 3;
const LOG_AM_IN_NULL = 4;
const LOG_AM_OUT_EARLY = 5;
const LOG_AM_OUT = 6;
const LOG_AM_OUT_LATE = 7;
const LOG_AM_OUT_NULL = 8;
const LOG_PM_IN_EARLY = 9;
const LOG_PM_IN = 10;
const LOG_PM_IN_LATE = 11;
const LOG_PM_IN_NULL = 12;
const LOG_PM_OUT_EARLY = 13;
const LOG_PM_OUT = 14;
const LOG_PM_OUT_LATE = 15;
const LOG_PM_OUT_NULL = 16;

// User Roles
const ROLE_ADMIN = 1;
const ROLE_MANAGER = 2;
const ROLE_EMPLOYEE = 3;

// Record Types
const AM_IN = 1;
const AM_OUT = 2;
const PM_IN = 3;
const PM_OUT = 4;