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

    public function __construct($err = null)
    {
        if($err && $err instanceof \Throwable) {
            $this->err = $err;
        }
    }

    protected function getHeaderText()
    {
        $botUsername = static::$botUsername;
        return "*$this->name* from [@$botUsername](https://t.me/$botUsername)".PHP_EOL.PHP_EOL;
    }

    protected function getErrorTrace()
    {
        $err = $this->err;
        $errMessage = $err->getMessage();
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

        return [
            'message' => $errMessage,
            'trace' => $errTraceData
        ];
    }

    protected function getErrorText()
    {
        $errData = $this->getErrorTrace();
        $text = $errData['message'];

        foreach($errData['trace'] as $errTrace) {
            $errFile = $errTrace['file'];
            $errLine = $errTrace['line'];
            $text .= "\n  at $errFile:$errLine";
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
        $headerText = $this->getHeaderText();
        $errorText = $this->getErrorText();
        $footerText = $this->getFooterText();

        return $headerText.$errorText.$footerText;
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