<?php
namespace MuhammadSabri1306\MyBotLogger\Entities;

use MuhammadSabri1306\MyBotLogger\Logger;

class MeekroDbLogger extends Logger
{
    protected $key = 'meekrodberror';
    protected $name = 'MeekroDB Error';

    public function getMessageText()
    {
        $this->setParams([
            'mysql_query' => $this->err->getQuery()
        ]);

        return $this->getHeaderText() .
            $this->getDescriptionText() .
            $this->getParamsText() .
            $this->getErrorText() .
            $this->getFooterText();
    }

    public static function catch($err)
    {
        $logData = new MeekroDbLogger($err);
        return $logData->log();
    }
}