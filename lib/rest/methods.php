<?php

namespace Rest;


class Methods
{
    public static function getAudiobookByName(string $bookName): ?string
    {
        $result = null;
        $searcher = new \Catalog\Searcher($bookName, (float)(\Config::getInstance()->get('similarityPercent') / 100), \Config::getInstance()->get('comparisonMethod'));
        if ($searcher->search() && $searcher->validate()) {
            $result = (new EntityJsonConverter(new \ORM\BookDataTable(), $searcher->getBookId(), ['NAME' => $searcher->getBookName()]))->convert();
        }
        
        return $result;
    }
}
