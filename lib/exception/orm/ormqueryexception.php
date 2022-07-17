<?php

namespace Exception\Orm;


use Throwable;

class OrmQueryException extends OrmException
{
    protected string $query;
    
    public function __construct($query, $message)
    {
        $this->query = $query;
        $message .= PHP_EOL . 'Query: ' . $this->query;
        parent::__construct($message);
    }
    
    public function getQuery(): string
    {
        return $this->query;
    }
}