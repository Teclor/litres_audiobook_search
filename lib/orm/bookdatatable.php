<?php

namespace ORM;


class BookDataTable extends AbstractTable
{
    public static function getName(): string
    {
        return 'book_data';
    }

    public static function getColumns(): array
    {
        return [
            'BOOK_ID',
            'IS_AVAILABLE',
            'URL',
            'PRICE',
            'CURRENCY_ID',
            'CATEGORY_ID',
            'PICTURE',
            'AUTHOR',
            'PUBLISHER',
            'SERIES',
            'YEAR',
            'ISBN',
            'PERFORMED_BY',
            'FORMAT',
            'DESCRIPTION',
            'IS_DOWNLOADABLE',
            'AGE',
            'LANG',
            'PARAM_FORMATS',
            'PARAM_FRAGMENT',
            'LITRES_ISBN',
            'GENRES_LIST',
        ];
    }

    public static function getPrimaryKey(): string|array
    {
        return 'BOOK_ID';
    }
}