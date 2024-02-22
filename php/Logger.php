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
    protected $err;

    protected $params = [];

    public function __construct($err = null)
    {
        if($err && $err instanceof \Throwable) {
            $this->err = $err;
        }
    }

    public function setParams(array $params)
    {
        foreach($params as $key => $val) {
            $this->params[$key] = $val;
        }
    }

    protected function getHeaderText()
    {
        $botUsername = static::$botUsername;
        return "*$this->name* from [@$botUsername](https://t.me/$botUsername)".PHP_EOL.PHP_EOL;
    }

    protected function getDescriptionText()
    {
        try {

            $errMessage = $this->err->getMessage();
            $escapedChars = [ '_', '*', '`', '[', ']' ];
            $text = str_replace($escapedChars, '', $errMessage);
            return $text . PHP_EOL;

        } catch(\Throwable $err) {
            return '';
        }
    }

    protected function getParamsText()
    {
        if(!isset($this->params)) return '';
        try {

            $json = json_encode($this->params, JSON_PRETTY_PRINT);
            return "Parameter:```" . PHP_EOL . "$json```" . PHP_EOL . PHP_EOL;

        } catch(\Throwable $err) {
            return '';
        }
    }

    protected function getErrorTrace()
    {
        $err = $this->err;
        $errTrace = array_filter($err->getTrace(), function($trace) {
            return isset($trace['file'], $trace['line']);
        });

        $errTraceData = [
            [ 'file' => $err->getFile(), 'line' => $err->getLine() ],
            ... array_map(function($errTrace) {
                return [
                    'file' => $errTrace['file'],
                    'line' => $errTrace['line']
                ];
            }, $errTrace)
        ];

        return $errTraceData;
    }

    protected function getErrorText()
    {
        $errTraceData = $this->getErrorTrace();
        $text = '';

        $useNewLine = false;
        foreach($errTraceData as $errTrace) {
            $errFile = $errTrace['file'];
            $errLine = $errTrace['line'];
            if($useNewLine) $text .= PHP_EOL;
            $text .= "  at $errFile:$errLine";
            if(!$useNewLine) $useNewLine = true;
        }

        return '```' . PHP_EOL . "$text```";
    }
    
    public function getFooterText()
    {
        $datetime = new \DateTime();
        $datetimeStr = $datetime->format('Y-m-d H:i:s');
        return PHP_EOL.PHP_EOL."#mybotlogger #$this->key $datetimeStr";
    }

    public function getMessageText()
    {
        return $this->getHeaderText() .
            $this->getDescriptionText() .
            $this->getParamsText() .
            $this->getErrorText() .
            $this->getFooterText();
    }
    
    public function log()
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

        return $response;
    }

    public static function addHook(string $key, callable $fn)
    {
        if(in_array($key, ['before', 'after'])) {
            static::$hooks[$key] = $fn;
        }
    }
}