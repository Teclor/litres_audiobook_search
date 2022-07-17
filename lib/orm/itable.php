<?php

namespace ORM;


interface ITable
{
    public static function getName(): string;
    public static function getColumns(): array;
    public static function getPrimaryKey(): string|array;
    public static function insert(array $values);
    public static function update($primaryKeyValue, array $values);
    public static function delete($primaryKeyValue);
    public static function select(array $conditions = [], array $columns = [], string $orderBy = '', $orderAsc = true): array;
}