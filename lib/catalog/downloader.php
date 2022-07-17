<?php

namespace Catalog;


class Downloader
{
    private string $downloadUrl;
    private string $savePath;
    
    public function __construct(string $downloadUrl, string $savePath)
    {
        $this->downloadUrl = $downloadUrl;
        $this->savePath = $savePath;
    }
    
    public function download()
    {
        $curl = curl_init($this->downloadUrl);
        $fileResource = fopen($this->savePath, 'wb');
        
        curl_setopt($curl, CURLOPT_FILE, $fileResource);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        
        curl_exec($curl);
        curl_close($curl);
        fclose($fileResource);
    }
}