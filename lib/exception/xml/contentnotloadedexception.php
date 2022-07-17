<?php

namespace Exception\Xml;


class ContentNotLoadedException extends \Exception
{
    private string $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        parent::__construct("Xml file content with path {$this->filePath} has not been correctly loaded into xml object");
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }
}