<?php

// Safe example configuration for local development.
// For local non-Docker use, copy this file to webconfig.local.php and edit the values.
// webconfig.local.php is ignored by Git so real credentials are not committed.

define('DB_DSN', getenv('DB_DSN') ?: 'mysql:host=localhost;dbname=wearview_supportdb;charset=utf8mb4');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
