<?php

namespace Error;

use File\File;

class Logger
{
    public const DEFAULT_LOG_PATH = 'log' . DIRECTORY_SEPARATOR . 'errors.log';

    public static function logMessage($message)
    {
        $message = date('d.m.Y H:i:s') . ' ' . $message . PHP_EOL;
        File::putContents(self::DEFAULT_LOG_PATH, $message, true);
    }
    
    public static function log($data)
    {
        $message = date('d.m.Y H:i:s') . ' ' . print_r($data, true) . PHP_EOL;
        File::putContents(self::DEFAULT_LOG_PATH, $message, true);
    }
    
    /**
     * Логирует сообщение и файл со строкой из исключения
     * @param \Throwable $exception
     */
    public static function logException(\Throwable $exception)
    {
        $errorData = date('d.m.Y H:i:s') . " {$exception->getMessage()}" . PHP_EOL ."{$exception->getFile()}:{$exception->getLine()}" . PHP_EOL;
        File::putContents(self::DEFAULT_LOG_PATH, $errorData, true);
    }
}
