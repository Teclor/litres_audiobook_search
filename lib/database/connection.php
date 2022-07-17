<?php

namespace Database;


use Exception\Orm\OrmQueryException;
use PDO;

class Connection
{
    private static self $instance;
    private PDO $pdo;
    
    private function __construct()
    {
        
    }
    
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function init(Auth $auth)
    {
        $dataSourceName =
            'mysql:host=' . $auth->getServername() .
            ';dbname=' . $auth->getDatabase() .
            ';charset=' . $auth->getCharset();
        $this->pdo = new PDO($dataSourceName, $auth->getUsername(), $auth->getPassword());
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function get(): PDO
    {
        return $this->pdo;
    }
    
    public function executeQuery(string $query): bool|array
    {
        try {
            $preparedQuery = $this->pdo->prepare($query);
            $preparedQuery->execute();
            return $preparedQuery->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (\PDOException $exception) {
            throw new OrmQueryException($query, $exception->getMessage());
        }
    }
}