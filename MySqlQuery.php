<?php
namespace RomanN44\MySqlQuery;

require_once('SqlQuery.php');
require_once('SqlQueryOffsetable.php');
require_once('SqlQueryLimitable.php');

class MySqlQuery implements SqlQuery, SqlQueryOffsetable, SqlQueryLimitable
{
    /**
     * @var string
     */ 
    private $select;
    
    /**
     * @var string
     */ 
    private $from;
    
    /**
     * @var string
     */
    private $where;

    /**
     * @var string
     */
    private $limit;

    /**
     * @var string
     */
    private $offset;
    

    public function select($columns = array("*"))
    {
        if (is_array($columns)) {

            foreach ($columns as $column) {

                if (is_array($column)) {
                    list($name, $alias) = $column;
                    $this->select .= "`{$name}` as `{$alias}`";
                } else {
                    $this->select .= "`{$column}`";
                }
                $this->select .= ',';
            }
        } else {
            
            $this->select = $columns;
        }
        return $this;
    }

    public function from($tableName)
    {
        if(is_array($tableName))
        {
            $this->from = "`{$tableName[0]}` as {$tableName[1]}"; //!!!
        } else {
            $this->form=$tableName;
        }
        return $this;
    }

    private function whereCondition(array $condition)
    {
        if (array_keys($condition) !== range(0, count($condition) - 1)) {
            $keys = array_keys($condition);
            $this->where .= "{$keys[0]} = {$condition[$keys[0]]}";
        } else {
            if (strcasecmp($condition[0], "in") == 0 || strcasecmp($condition[0], "not in") == 0) {
                $this->where .= "{$condition[1]} {$condition[0]} ( ";
                foreach ($condition[2] as $value) {
                    $this->where .= "{$value}, ";
                }
                $this->where = substr($this->where, 0, strlen($this->where) - 2) . ")";
            } else {
                $this->where .= "{$condition[1]} {$condition[0]} {$condition[2]}";
            }
        }
    }

    public function andWhere(array $condition)
    {
        if (!is_null($this->where)) {
            $this->where .= " AND ";
        }
        $this->whereCondition($condition);
        return $this;
    }

    public function orWhere(array $condition)
    {
        if (!is_null($this->where)) {
            $this->where .= " OR ";
        }
        $this->whereCondition($condition);
        return $this;
    }

    public function limit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function getRaw(): string
    {
        $query = "SELECT {$this->select} FROM {$this->from}";

        if(!empty($this->where))
        {
            $query .= " WHERE {$this->where}";
        }

        if(!empty($this->limit))
        {
            $query .= " LIMIT {$this->limit}";
        }

        if(!empty($this->where))
        {
            $query .= " OFFSET {$this->offset}";
        }

        return $query;
    }

    private function by($args)
    {
        $str = "";
        foreach ($args as $column) {

            if (!is_array($column)) {
                throw new Exception("Ошибка!");
            }
            foreach ($column as $cell) {
                $str .= " {$cell}";
            }
            $str .= ",";
        }
        return substr($str, 0, strlen($str) - 1);
    }

    public function orderBy($args)
    {
        $this->orderBy = $this->by($args);
        return $this;
    }

    public function groupBy($args)
    {
        $this->groupBy = $this->by($args);
        return $this;
    }

}
?>