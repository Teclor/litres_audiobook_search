<?php

class Config extends \Pattern\Singleton
{
    public const CONFIG_FILE_PATH = '/resources/config.json';
    private array $config;
    
    public function load()
    {
        $this->config = json_decode(\File\File::getContents(self::CONFIG_FILE_PATH), true);
    }
    
    public function get($paramName)
    {
        if (!isset($this->config)) {
            $this->load();
        }
        return $this->config[$paramName];
    }
}