<?php
namespace MuhammadSabri1306\MyBotLogger\Entities;

use MuhammadSabri1306\MyBotLogger\Logger;

class MeekroDbLogger extends Logger
{
    protected $key = 'meekrodberror';
    protected $name = 'MeekroDB Error';

    protected function getQueryText()
    {
        $query = $this->err->getQuery();
        if(empty($query)) return '';
        return PHP_EOL.PHP_EOL."MySQL Query:```".PHP_EOL."$query```";
    }

    public function getMessageText()
    {
        $headerText = $this->getHeaderText();
        $errorText = $this->getErrorText();
        $queryText = $this->getQueryText();
        $footerText = $this->getFooterText();

        return $headerText.$errorText.$queryText.$footerText;
    }

    public static function catch($err)
    {
        $logData = new MeekroDbLogger();
        $logData->err = $err;
        return $logData->log();
    }
}