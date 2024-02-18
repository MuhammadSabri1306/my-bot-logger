<?php
namespace MuhammadSabri1306\MyBotLogger\Entities;

use MuhammadSabri1306\MyBotLogger\Logger;

class HttpClientLogger extends Logger
{
    protected $key = 'httpclient';
    protected $name = 'HTTP Client';

    protected function getRequestText()
    {
        try {

            $request = $this->err->getRequest();
            $json = json_encode($request, JSON_PRETTY_PRINT);
            return PHP_EOL.PHP_EOL."Request:```".PHP_EOL."$json```";

        } catch(\Throwable $err) {
            return '';
        }
    }

    protected function getResponseText()
    {
        try {

            $response = $this->err->getResponse();
            $json = json_encode($response, JSON_PRETTY_PRINT);
            return PHP_EOL.PHP_EOL."Response:```".PHP_EOL."$json```";

        } catch(\Throwable $err) {
            return '';
        }
    }

    public function getErrorText()
    {
        $requestText = $this->getRequestText();
        $responseText = $this->getResponseText();
        return $requestText.$responseText;
    }

    public static function catch($err)
    {
        $logData = new HttpClientLogger($err);
        return $logData->log();
    }
}