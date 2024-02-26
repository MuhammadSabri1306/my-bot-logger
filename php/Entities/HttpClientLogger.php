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

            $text = null;
            if(is_string($errMessage) && !empty($errMessage)) {

                // remove response from error message
                $responseStrIndex = strpos($errMessage, ' response:');
                $text = $responseStrIndex === false ? $errMessage : substr($errMessage, 0, $responseStrIndex);

                // escape telegram markdown
                $escapedChars = [ '_', '*', '`', '[', ']' ];
                foreach($escapedChars as $char) {
                    $text = str_replace($char, "\\$char", $text);
                }

            }

            if(!is_string($text) || empty($text)) {
                $text = 'error uncaught';
            }

            return  PHP_EOL . $text . PHP_EOL;

        } catch(\Throwable $err) {
            return '';
        }
    }

    protected function getRequestText()
    {
        try {

            $request = $this->err->getRequest();
            if(empty($request)) return '';

            $json = json_encode($request, JSON_PRETTY_PRINT);
            return PHP_EOL . "Request:```" . PHP_EOL . "$json```" . PHP_EOL;

        } catch(\Throwable $err) {
            return '';
        }
    }

    protected function getResponseText()
    {
        try {

            $response = $this->err->getResponse();
            if(empty($response)) return '';

            $json = json_encode($response, JSON_PRETTY_PRINT);
            return PHP_EOL . "Response:```" . PHP_EOL . "$json```" . PHP_EOL;

        } catch(\Throwable $err) {
            return '';
        }
    }

    public function getMessageText()
    {
        return $this->getHeaderText() .
            $this->getDescriptionText() .
            $this->getErrorText() .
            $this->getParamsText() .
            $this->getRequestText().
            $this->getResponseText().
            $this->getFooterText();
    }

    public static function catch($err)
    {
        $logger = new HttpClientLogger($err);
        return $logger->log();
    }
}