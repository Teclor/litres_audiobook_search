<?php

namespace Database;


use \Exception\Orm\OrmQueryException;
use \Pattern\Singleton;
use \PDO;

class Connection extends Singleton
{
    private PDO $pdo;
    
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

    public function executeQueryWithBinds(string $query, array $bindToValue): bool|array
    {
        try {
            $preparedQuery = $this->pdo->prepare($query);
            foreach ($bindToValue as $bind => $replacement) {
                if (is_array($replacement)) {
                    foreach ($replacement as $index => $value) {
                        $preparedQuery->bindValue($index + 1, $value, self::getPdoType($value));
                    }
                }
                else {
                    $preparedQuery->bindValue($bind, $replacement, self::getPdoType($replacement));
                }
            }
            $preparedQuery->execute();
            return $preparedQuery->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (\PDOException $exception) {
            throw new OrmQueryException($query, $exception->getMessage(), $bindToValue);
        }
    }
    
    public static function getPdoType($value)
    {
        if (is_int($value)) {
            $type = PDO::PARAM_INT;
        }
        elseif (is_string($value) || is_float($value)) {
            $type = PDO::PARAM_STR;
        }
        elseif (is_bool($value)) {
            $type = PDO::PARAM_BOOL;
        }
        elseif (is_null($value)) {
            $type = PDO::PARAM_NULL;
        }
        else {
            $type = false;
        }
        return $type;
    }
}