<?php
set_time_limit(86400);
$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__);

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'init.php';

try {
    $config = Config::getInstance();
    $downloadPath = $config->get('downloadFilePath');
    $downloadUrl = $config->get('downloadUrl');
    $filesDir = $config->get('filesDir');
    $xmlFileName = $config->get('xmlFileName');
    
    if ($downloadUrl && $downloadPath && $filesDir && $xmlFileName) {
        (new \Catalog\Downloader($downloadUrl,  \File\File::getAbsolutePath($downloadPath)))->download();
        \File\File::putContents($filesDir . $xmlFileName, gzdecode(\File\File::getContents($downloadPath)));

        $parser = new \Catalog\XmlParser(\File\File::getAbsolutePath($filesDir . $xmlFileName));
        $offers = $parser->getOffers();

        \Catalog\Updater::updateOffers($offers);
    }
    else {
        \Error\Logger::logMessage("Unable to download file from $downloadUrl to $downloadPath and extract to $filesDir");
    }
}
catch (\Throwable $exception) {
    \Error\Logger::logException($exception);
}
