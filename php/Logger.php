<?php
namespace MuhammadSabri1306\MyBotLogger;

class Logger
{
    public static $chatId;
    public static $botToken;
    public static $botUsername;

    private static $hooks = [];
    
    protected $key = 'phperror';
    protected $name = 'PHP Server Error';
    protected $message;
    protected $tracedData;

    protected function getTracedText()
    {
        return json_encode($this->tracedData, JSON_INVALID_UTF8_IGNORE);
    }

    public function getMessageText()
    {
        $botUsername = static::$botUsername;
        $title = "*$this->name* from [@$botUsername](https://t.me/$botUsername)";
        $description = "$this->message in";

        $tracedText = $this->getTracedText();
        $tracedContent = "";

        $datetime = new \DateTime();
        $datetimeStr = $datetime->format('Y-m-d H:i:s');
        $footer = "#mybotlogger #$this->key $datetimeStr";

        return $title.PHP_EOL.PHP_EOL."$description```".PHP_EOL."$tracedText```".PHP_EOL.PHP_EOL.$footer;
    }
    
    protected function log()
    {
        $botToken = static::$botToken;
        $chatId = static::$chatId;
        $text = urlencode($this->getMessageText());
        $url = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&parse_mode=markdown&text=$text";
        
        if(isset(static::$hooks['before']) && is_callable(static::$hooks['before'])) {
            static::$hooks['before']($url);
        }

        $response = file_get_contents($url);
        
        if(isset(static::$hooks['after']) && is_callable(static::$hooks['after'])) {
            static::$hooks['after']($response, $url);
        }
    }

    public static function addHook(string $key, callable $fn)
    {
        if(in_array($key, ['before', 'after'])) {
            static::$hooks[$key] = $fn;
        }
    }
}