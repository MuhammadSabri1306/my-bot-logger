<?php
namespace MuhammadSabri1306\MyBotLogger\Entities;

use MuhammadSabri1306\MyBotLogger\Logger;

class TelegramResponseLogger extends Logger
{
    protected $key = 'telegramerror';
    protected $name = 'Telegram Response Error';
    public $responseData;

    protected function getResponseText()
    {
        return json_encode($this->responseData, JSON_PRETTY_PRINT);
    }

    protected function getTracedText()
    {
        $tracedLines = array_map(function($item) {
            $file = $item['file'];
            $line = $item['line'];
            return "  $file at line $line";
        }, $this->tracedData);

        return implode(PHP_EOL, $tracedLines);
    }

    public function getMessageText()
    {
        $botUsername = static::$botUsername;
        $title = "*$this->name* from [@$botUsername](https://t.me/$botUsername)";
        $description = $this->getDescriptionText();

        $tracedText = $this->getTracedText();
        $responseText = $this->getResponseText();

        $datetime = new \DateTime();
        $datetimeStr = $datetime->format('Y-m-d H:i:s');
        $footer = "#mybotlogger #$this->key $datetimeStr";

        return $title.PHP_EOL.PHP_EOL.
            "$description```".PHP_EOL."$tracedText```".PHP_EOL.PHP_EOL.
            "Response:```".PHP_EOL."$responseText```".PHP_EOL.PHP_EOL.
            $footer;
    }

    public static function catch($err)
    {
        $logData = new TelegramResponseLogger();
        $logData->message = $err->getMessage();
        $logData->responseData = $err->getResponseData();

        $logData->tracedData = array_reduce(
            $err->getTrace(),
            function($trace, $item) {
                array_push($trace, [
                    'file' => $item['file'] ?? null,
                    'line' => $item['line'] ?? null
                ]);

                return $trace;
            },
            [
                [
                    'file' => $err->getFile() ?? null,
                    'line' => $err->getLine() ?? null
                ]
            ]
        );

        return $logData->log();
    }
}