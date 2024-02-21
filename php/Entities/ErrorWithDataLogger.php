<?php
namespace MuhammadSabri1306\MyBotLogger\Entities;

use MuhammadSabri1306\MyBotLogger\Logger;

class ErrorWithDataLogger extends Logger
{
    public $data;

    public function getDataText()
    {
        if(!isset($this->data)) return '';
        try {

            $json = json_encode($this->data, JSON_PRETTY_PRINT);
            return PHP_EOL.PHP_EOL."Data:```".PHP_EOL."$json```";

        } catch(\Throwable $err) {
            return '';
        }
    }

    public function getErrorText()
    {
        $dataText = $this->getDataText();
        $errorText = PHP_EOL.PHP_EOL.'Error:'.PHP_EOL.parent::getErrorText();
        return $dataText.$errorText;
    }

    public static function catch(\Throwable $err, array $data = [])
    {
        $logger = new ErrorLogger($err);
        $logger->data = $data;
        return $logger->log();
    }
}