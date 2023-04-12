<?php

/* Path to the WordPress codebase you'd like to test. Add a backslash in the end. */
define('ABSPATH', str_ireplace("\\", "/", dirname(__FILE__)) . '/../../');

// Load WordPress Core
require_once(ABSPATH . "wp-load.php");

// Load Package
require_once(str_ireplace("\\", "/", dirname(__FILE__)) . '/../vendor/autoload.php');




