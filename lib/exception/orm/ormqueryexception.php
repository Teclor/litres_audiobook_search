<?php

namespace Exception\Orm;


use Throwable;

class OrmQueryException extends OrmException
{
    protected string $query;
    
    public function __construct($query, $message, $binds = [])
    {
        $this->query = $query;
        $message .= PHP_EOL . 'Query: ' . $this->query;
        if (!empty($binds)) {
            $message .= PHP_EOL . 'Binds: ' . print_r($binds, true);
        }
        parent::__construct($message);
    }
    
    public function getQuery(): string
    {
        return $this->query;
    }
}