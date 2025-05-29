<?php
require_once __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Membuat instance logger
$log = new Logger('app_logger');

// Menentukan handler untuk simpan log ke file
$log->pushHandler(new StreamHandler(__DIR__ . '/logs/app.log', Logger::DEBUG));

// Fungsi untuk mencatat aktivitas pengguna
function logActivity($message)
{
    global $log;
    $log->info('[ACTIVITY] ' . $message);
}

// Fungsi untuk mencatat error
function logError($message)
{
    global $log;
    $log->error('[ERROR] ' . $message);
}
