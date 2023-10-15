<?php
namespace MuhammadSabri1306\MyBotLogger\Entities;

use MuhammadSabri1306\MyBotLogger\Logger;

class ErrorLogger extends Logger
{
    protected function getTracedText()
    {
        $tracedLines = array_map(function($item) {
            $file = $item['file'];
            $line = $item['line'];
            return "  $file at line $line";
        }, $this->tracedData);

        return implode(PHP_EOL, $tracedLines);
    }

    public static function catch($err)
    {
        $logData = new ErrorLogger();
        $logData->message = $err->getMessage();

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