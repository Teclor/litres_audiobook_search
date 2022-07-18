<?php

namespace ORM;


use Database\Connection;

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
    
    public static function getSearchedName($searchText): array
    {
        $query = "SELECT *, match(NAME) AGAINST ('$searchText') AS RELEVANCE FROM " . self::getName() . 
            " WHERE match(NAME) AGAINST ('$searchText') ORDER BY RELEVANCE DESC LIMIT 1";
        $result = Connection::getInstance()->executeQuery($query);
        
        return is_array(current($result)) ? current($result) : [];
    }
}