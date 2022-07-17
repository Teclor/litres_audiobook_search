<?php

namespace Database;


use File\File;

class Auth implements \JsonSerializable
{
    public const DEFAULT_AUTH_FILE_PATH = DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'db_auth.json';
    
    private string $servername;
    private string $database;
    private string $username;
    private string $password;
    private string $charset;
    
    private function __construct()
    {
        
    }
    
    public static function initFromParams(string $servername, string $database, string $username, string $password, string $charset): self
    {
        $self = new self();
        $self->servername = $servername;
        $self->database = $database;
        $self->username = $username;
        $self->password = $password;
        $self->charset = $charset;
        
        return $self;
    }
    
    /**
     * @throws \Exception\File\FileNotFoundException
     * @throws \Exception
     */
    public static function initFromFile(string $filePath = ''): self
    {
        $self = new self();
        if ($filePath === '') {
            $filePath = self::DEFAULT_AUTH_FILE_PATH;
        }
        $fileContent = File::getContents($filePath);
        $jsonContent = json_decode($fileContent, true);
        
        $properties = (new \ReflectionClass(self::class))->getProperties();
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if (isset($jsonContent[$propertyName])) {
                $self->{$propertyName} = $jsonContent[$propertyName];
            }
            else {
                throw new \Exception("Property $propertyName not found in $filePath");
            }
        }
        
        return $self;
    }
    
    public function getServername(): string
    {
        return $this->servername;
    }
    
    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
    
    public function getPassword(): string
    {
        return $this->password;
    }
    
    public function getCharset(): string
    {
        return $this->charset;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}