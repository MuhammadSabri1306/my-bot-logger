<?php
namespace MuhammadSabri1306\MyBotLogger\Entities;

use MuhammadSabri1306\MyBotLogger\Logger;

class WarningLogger extends Logger
{
    protected function getErrorTrace()
    {
        $errData = parent::getErrorTrace();
        return [
            'message' => $errData['message'],
            'trace' => array_slice($errData['trace'], 1)
        ];
    }

    public static function catch($err)
    {
        $logData = new WarningLogger();
        $logData->err = $err;
        return $logData->log();
    }
}