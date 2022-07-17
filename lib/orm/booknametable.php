<?php

namespace ORM;


class BookNameTable extends AbstractTable
{
    public static function getName(): string
    {
        return 'book_name';
    }
    
    public static function getColumns(): array
    {
        return ['BOOK_ID', 'NAME'];
    }
    
    public static function getPrimaryKey(): string|array
    {
        return 'BOOK_ID';
    }
}