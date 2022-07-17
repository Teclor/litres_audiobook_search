<?php

namespace Error;

use File\File;

class Logger
{
    public const DEFAULT_LOG_PATH = 'log' . DIRECTORY_SEPARATOR . 'errors.log';

    /**
     * Логирует сообщение и файл со строкой из исключения
     * @param \Throwable $exception
     */
    public static function logException(\Throwable $exception)
    {
        $errorData = "{$exception->getMessage()}\n{$exception->getFile()}:{$exception->getLine()}";
        File::putContents(self::DEFAULT_LOG_PATH, $errorData);
    }
}
