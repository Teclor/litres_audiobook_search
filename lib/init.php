<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    $libPath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR;
    $pathFromNamespace = str_replace('\\', DIRECTORY_SEPARATOR, strtolower($class)) . '.php';
    include_once $libPath . $pathFromNamespace;
});

try {
    \Database\Connection::getInstance()->init(\Database\Auth::initFromFile());
}
catch (\Throwable $exception) {
    \Error\Logger::logException($exception);
}
