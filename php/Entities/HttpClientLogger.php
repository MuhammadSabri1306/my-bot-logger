<?php
namespace MuhammadSabri1306\MyBotLogger\Entities;

use MuhammadSabri1306\MyBotLogger\Logger;

class HttpClientLogger extends Logger
{
    protected $key = 'httpclient';
    protected $name = 'HTTP Client';

    protected function getDescriptionText()
    {
        try {

            $errMessage = $this->err->getMessage();
            if(!$errMessage) {
                $response = $this->err->getResponse();
                if(isset($response->message)) {
                    $errMessage = $response->message;
                } elseif(isset($response['message'])) {
                    $errMessage = $response['message'];
                }
            }

            $escapedChars = [ '_', '*', '`', '[', ']' ];
            $text = str_replace($escapedChars, '', $errMessage);
            return $text . PHP_EOL;

        } catch(\Throwable $err) {
            return '';
        }
    }

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

    public function getMessageText()
    {
        return $this->getHeaderText() .
            $this->getParamsText() .
            $this->getErrorText() .
            $this->getRequestText().
            $this->getResponseText().
            $this->getFooterText();
    }

    public static function catch($err)
    {
        $logData = new HttpClientLogger($err);
        return $logData->log();
    }
}