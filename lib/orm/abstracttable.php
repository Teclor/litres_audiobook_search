<?php

namespace ORM;


use Database\Connection;
use Exception\Method\NotImplementedException;
use Exception\Orm\OrmException;

abstract class AbstractTable implements ITable
{
    protected const ALLOWED_COMPARISON_OPERATORS = ['=', '>', '<', '>=', '<=', '!=', '<>', '<=>'];
    
    public static function getName(): string
    {
        throw new NotImplementedException(__CLASS__, __METHOD__);
    }
    
    public static function getColumns(): array
    {
        throw new NotImplementedException(__CLASS__, __METHOD__);
    }
    
    public static function getPrimaryKey(): string|array
    {
        throw new NotImplementedException(__CLASS__, __METHOD__);
    }

    public static function insert(array $values)
    {
        $query = 'INSERT INTO ' . static::getName();
        $columnToValue = self::getValuesByColumnNames($values);
        
        if (count($columnToValue) > 0) {
            $query .= ' (' . implode(', ', array_keys($columnToValue)) . ') ';
            $query .= 'VALUES (\'' . implode('\', \'', $columnToValue) . '\')';
        }
        $query .= ';';
        Connection::getInstance()->executeQuery($query);
    }

    /**
     * @throws OrmException
     */
    public static function update($primaryKeyValue, array $values)
    {
        $query = 'UPDATE ' . static::getName() . ' SET ';
        $columnToValue = self::getValuesByColumnNames($values);
        $isCommaRequired = false;
        foreach ($columnToValue as $columnName => $value) {
            if ($isCommaRequired) {
                $query .= ', ';
            }
            $query .= $columnName . " = '$value'";
            $isCommaRequired = true;
        }
        $query .= static::getWhereForPrimary($primaryKeyValue) . ';';
        Connection::getInstance()->executeQuery($query);
    }

    public static function delete($primaryKeyValue)
    {
        $query = 'DELETE FROM ' . static::getName() . ' ' . static::getWhereForPrimary($primaryKeyValue) . ';';
        Connection::getInstance()->executeQuery($query);
    }

    public static function select(array $conditions = [], array $columns = [], string $orderBy = '', $orderAsc = true): array
    {
        $query = 'SELECT ';
        if (count($columns) > 0) {
            $isCommaRequired = false;
            foreach ($columns as $alias => $columnName) {
                if ($isCommaRequired) {
                    $query .= ', ';
                }
                $query .= $columnName;
                if (is_string($alias)) {
                    $query .= " AS $alias";
                }
                $isCommaRequired = true;
            }
        }
        else {
            $query .= ' * ';
        }
        
        $query .= ' FROM ' . static::getName();
        if (count($conditions) > 0) {
            $query .= ' ' . static::getWhereForConditions($conditions);
        }
        
        if (!empty($orderBy)) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . ($orderAsc ? 'ASC' : 'DESC');
        }
        
        $query .= ';';
        return Connection::getInstance()->executeQuery($query) ?: [];
    }
    
    protected static function getValuesByColumnNames($values): array
    {
        $columnToValue = [];
        foreach (static::getColumns() as $columnName) {
            if (isset($values[$columnName])) {
                $columnToValue[$columnName] = $values[$columnName];
            }
        }
        
        return $columnToValue;
    }

    protected static function getWhereForConditions(array $conditions): string
    {
        $whereCondition = 'WHERE ';
        $isAndRequired = false;
        foreach ($conditions as $columnName => $columnValue) {
            if ($isAndRequired) {
                $whereCondition .= ' AND ';
            }
            if (is_array($columnValue)) {
                $whereCondition .= $columnName . ' IN (' . implode(',', $columnValue) . ')';
            }
            else {
                $comparisonOperator = '';
                for ($operatorLength = 3; $operatorLength >= 1; $operatorLength--) {
                    if (in_array(substr($columnName, 0, $operatorLength), static::ALLOWED_COMPARISON_OPERATORS)) {
                        $comparisonOperator = substr($columnName, 0, $operatorLength);
                        $columnName = substr($columnName, $operatorLength);
                    }
                }
                if (empty($comparisonOperator)) {
                    $whereCondition .= "$columnName LIKE '%$columnValue%'";
                }
                else {
                    $whereCondition .= "$columnName $comparisonOperator '$columnValue'";
                }
            }
            $isAndRequired = true;
        }
        
        return $whereCondition;
    }
    
    /**
     * @throws OrmException
     */
    protected static function getWhereForPrimary($primaryKeyValue): string
    {
        if (empty($primaryKeyValue)) {
            throw new OrmException('Empty primary key value');
        }
        $whereCondition = 'WHERE ';
        $primaryKey = static::getPrimaryKey();
        if (is_string($primaryKey)) {
            $whereCondition .= $primaryKey;
            if (is_array($primaryKeyValue)) {
                $whereCondition .= ' IN (' . implode(',', $primaryKeyValue) . ')';
            }
            else {
                $whereCondition .= " = '$primaryKeyValue'";
            }
        }
        else {
            throw new \Exception('Only string primary key support implemented');
        }
        
        return $whereCondition;
    }
 }