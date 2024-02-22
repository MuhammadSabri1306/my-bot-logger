<?php
namespace MuhammadSabri1306\MyBotLogger\Entities;

use MuhammadSabri1306\MyBotLogger\Logger;

class ErrorLogger extends Logger
{
    public static function catch($err)
    {
        $logData = new ErrorLogger($err);
        return $logData->log();
    }
}