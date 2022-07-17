<?php


namespace Catalog;


use \Error\Logger;
use \ORM\BookDataTable;
use \ORM\BookNameTable;

class Updater
{
    public const XML_TO_DATA_TABLE_FIELD_MAP = [
        'id' => 'BOOK_ID',
        'available' => 'IS_AVAILABLE',
        'url' => 'URL',
        'price' => 'PRICE',
        'currencyId' => 'CURRENCY_ID',
        'categoryId' => 'CATEGORY_ID',
        'picture' => 'PICTURE',
        'author' => 'AUTHOR',
        'publisher' => 'PUBLISHER',
        'series' => 'SERIES',
        'year' => 'YEAR',
        'ISBN' => 'ISBN',
        'performed_by' => 'PERFORMED_BY',
        'format' => 'FORMAT',
        'description' => 'DESCRIPTION',
        'downloadable' => 'IS_DOWNLOADABLE',
        'age' => 'AGE',
        'lang' => 'LANG',
        'Форматы' => 'PARAM_FORMATS',
        'Фрагмент' => 'PARAM_FRAGMENT',
        'litres_isbn' => 'LITRES_ISBN',
        'genres_list' => 'GENRES_LIST',
    ];
    
    public const BOOK_ID_FIELD = 'id';
    public const NAME_FIELD = 'name';
    
    public static function updateOffers(array $offers)
    {
        $bookIds = array_column($offers, self::BOOK_ID_FIELD);
        if (empty($bookIds)) {
            return;
        }
        $existingBookIds = array_column(BookNameTable::select([BookNameTable::getPrimaryKey() => $bookIds]), BookNameTable::getPrimaryKey());
        foreach ($offers as $offer) {
            $bookId = (int)$offer[self::BOOK_ID_FIELD];
            $bookName = (string)$offer[self::NAME_FIELD];
            if ($bookId < 0 || $bookName === '') {
                continue;
            }
            $bookData = [BookDataTable::getPrimaryKey() => $bookId];
            foreach ($offer as $fieldKey => $fieldValue) {
                if (isset(self::XML_TO_DATA_TABLE_FIELD_MAP[$fieldKey])) {
                    if (str_starts_with(self::XML_TO_DATA_TABLE_FIELD_MAP[$fieldKey], 'IS_')) {
                        $fieldValue = $fieldValue === 'true';
                    }
                    $bookData[self::XML_TO_DATA_TABLE_FIELD_MAP[$fieldKey]] = $fieldValue;
                }
            }
            
            try {
                if (in_array($bookId, $existingBookIds)) {
                    BookNameTable::update($bookId, ['NAME' => $bookName]);
                    unset($bookData[BookDataTable::getPrimaryKey()]);
                    BookDataTable::update($bookId, $bookData);
                }
                else {
                    BookNameTable::insert([BookNameTable::getPrimaryKey() => $bookId, 'NAME' => $bookName]);
                    BookDataTable::insert($bookData);
                }
            }
            catch (\Throwable $exception) {
                Logger::logException($exception);
            }
        }
    }
}