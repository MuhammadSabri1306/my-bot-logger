<?php
namespace MuhammadSabri1306\MyBotLogger\Entities;

use MuhammadSabri1306\MyBotLogger\Logger;

class TelegramResponseLogger extends Logger
{
    protected $key = 'telegramerror';
    protected $name = 'Telegram Response Error';

    protected function getResponseText()
    {
        try {

            $response = $this->err->getResponseData();
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
            $this->getResponseText().
            $this->getFooterText();
    }

    public static function catch($err)
    {
        $logger = new TelegramResponseLogger($err);
        return $logger->log();
    }
}