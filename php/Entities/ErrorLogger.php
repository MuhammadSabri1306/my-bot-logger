<?php
namespace MuhammadSabri1306\MyBotLogger\Entities;

use MuhammadSabri1306\MyBotLogger\Logger;

class ErrorLogger extends Logger
{
    public static function catch($err)
    {
        $logger = new ErrorLogger($err);
        return $logger->log();
    }
}