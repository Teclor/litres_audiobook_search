<?php

namespace Exception\File;


class FileNotFoundException extends \Exception
{
    private string $filePath;
    
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        parent::__construct("File with path {$this->filePath} found");
    }
    
    public function getFilePath(): string
    {
        return $this->filePath;
    }
}