<?php
namespace RomanN44\MySqlQuery;

interface SqlQuery
{
    public function select($columns = array("*"));
    public function from($tableName);
    public function andWhere(array $condition);
    public function orWhere(array $condition);
    public function orderBy($columns);
    public function groupBy($columns);
    
    public function getRaw(): string;
}
?>