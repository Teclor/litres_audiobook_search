<?php

namespace File;

use \Exception\File\FileNotFoundException;


class File
{
    /**
     * @throws FileNotFoundException
     */
    public static function getContents(string $pathFromDocumentRoot): bool|string
    {
        $absolutePath = self::getAbsolutePath($pathFromDocumentRoot);
        if (file_exists($absolutePath)) {
            return file_get_contents($absolutePath);
        }
        else {
            throw new FileNotFoundException($absolutePath);
        }
    }
    
    public static function putContents(string $pathFromDocumentRoot, $data, bool $append = false)
    {
        $absolutePath = self::getAbsolutePath($pathFromDocumentRoot);
        if ($append) {
            file_put_contents($absolutePath, $data, FILE_APPEND);
        }
        else {
            file_put_contents($absolutePath, $data);
        }
    }
    
    public static function getAbsolutePath(string $pathFromDocumentRoot): string
    {
        if (substr($pathFromDocumentRoot, 0, 1) !== DIRECTORY_SEPARATOR) {
            $pathFromDocumentRoot = DIRECTORY_SEPARATOR . $pathFromDocumentRoot;
        }
        return $_SERVER['DOCUMENT_ROOT'] . $pathFromDocumentRoot;
    }
}