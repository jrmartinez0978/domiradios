<?php

// API proxy for Android app (calls panel.domiradios.com.do/api/api.php)
// Forwards to Laravel's front controller

$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__.'/../index.php';
$_SERVER['PHP_SELF'] = '/index.php';

require __DIR__.'/../index.php';
