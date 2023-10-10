<?php
require_once 'autoload.php';
require __DIR__.'/setup-logger.php';

use MuhammadSabri1306\MyBotLogger\Logger;
use MuhammadSabri1306\MyBotLogger\Entities\ErrorLogger;

Logger::addHook('after', function($response) {
    header('Content-Type: application/json; charset=utf-8');
    echo $response;
});

try {
    callDb();
} catch(\Error $err) {
    ErrorLogger::catch($err);
}