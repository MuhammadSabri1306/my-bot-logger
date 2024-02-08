<?php
namespace MuhammadSabri1306\MyBotLogger\Entities;

use MuhammadSabri1306\MyBotLogger\Logger;

class TelegramResponseLogger extends Logger
{
    protected $key = 'telegramerror';
    protected $name = 'Telegram Response Error';

    protected function getResponseText()
    {
        $text = json_encode($this->err->getResponseData(), JSON_PRETTY_PRINT);
        return PHP_EOL.PHP_EOL."Response:```".PHP_EOL."$text```";
    }

    public function getMessageText()
    {
        $headerText = $this->getHeaderText();
        $errorText = $this->getErrorText();
        $responseText = $this->getResponseText();
        $footerText = $this->getFooterText();

        return $headerText.$errorText.$responseText.$footerText;
    }

    public static function catch($err)
    {
        $logData = new TelegramResponseLogger();
        $logData->err = $err;
        return $logData->log();
    }
}