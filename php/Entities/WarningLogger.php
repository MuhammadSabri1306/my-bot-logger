<?php
namespace MuhammadSabri1306\MyBotLogger\Entities;

use MuhammadSabri1306\MyBotLogger\Logger;

class WarningLogger extends Logger
{
    protected function getErrorTrace()
    {
        $errTraceData = parent::getErrorTrace();
        return array_slice($errTraceData, 1);
    }

    public static function catch($err)
    {
        $logData = new WarningLogger($err);
        return $logData->log();
    }
}