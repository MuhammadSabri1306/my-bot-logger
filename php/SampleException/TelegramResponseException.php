<?php
namespace MuhammadSabri1306\MyBotLogger\SampleException;

use Longman\TelegramBot\Entities\ServerResponse;
/* 
 * Server Response is an entity from https://github.com/php-telegram-bot/core
 */

class TelegramResponseException extends \Exception
{
    private $response;

    public function __construct(ServerResponse $response)
    {
        parent::__construct($response->printError(true));
        $this->response = $response->raw_data;
    }

    public function getResponseData()
    {
        return $this->response;
    }
}