<?php

// Legacy API endpoint - forwards to Laravel's front controller
// This file exists because nginx intercepts .php URLs before Laravel can route them

// Override SCRIPT_NAME and SCRIPT_FILENAME so Laravel resolves routes correctly
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__.'/../../index.php';
$_SERVER['PHP_SELF'] = '/index.php';

require __DIR__.'/../../index.php';
