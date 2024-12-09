<?php

namespace app\config;

class queryBuilder
{
    private $table;
    private $where = [];
    private $params = [];
    private $orderBy = '';
    private $limit = '';
    private $joins = [];
    private $paramCount = 0;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function where($column, $operator, $value)
    {
        $param = ':param' . ++$this->paramCount;
        $this->where[] = "$column $operator $param";
        $this->params[$param] = $value;
        return $this;
    }

    public function whereIn($column, array $values)
    {
        $params = [];
        foreach ($values as $value) {
            $param = ':param' . ++$this->paramCount;
            $params[] = $param;
            $this->params[$param] = $value;
        }
        $this->where[] = "$column IN (" . implode(', ', $params) . ")";
        return $this;
    }

    public function join($table, $first, $operator, $second, $type = 'INNER')
    {
        $this->joins[] = "$type JOIN $table ON $first $operator $second";
        return $this;
    }

    public function orderBy($column, $direction = 'ASC')
    {
        $this->orderBy = "ORDER BY $column $direction";
        return $this;
    }

    public function limit($limit, $offset = 0)
    {
        $this->limit = "LIMIT $offset, $limit";
        return $this;
    }

    public function getSelectQuery()
    {
        $query = "SELECT * FROM {$this->table}";
        
        if (!empty($this->joins)) {
            $query .= ' ' . implode(' ', $this->joins);
        }
        
        if (!empty($this->where)) {
            $query .= ' WHERE ' . implode(' AND ', $this->where);
        }
        
        if ($this->orderBy) {
            $query .= ' ' . $this->orderBy;
        }
        
        if ($this->limit) {
            $query .= ' ' . $this->limit;
        }
        
        return [$query, $this->params];
    }

    public function getInsertQuery($data)
    {
        $columns = implode(', ', array_keys($data));
        $params = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $param = ':param' . ++$this->paramCount;
            $params[$param] = $value;
            $values[] = $param;
        }
        
        $valuesStr = implode(', ', $values);
        $query = "INSERT INTO {$this->table} ($columns) VALUES ($valuesStr)";
        
        return [$query, $params];
    }

    public function getUpdateQuery($data)
    {
        $sets = [];
        $params = [];
        
        foreach ($data as $key => $value) {
            $param = ':param' . ++$this->paramCount;
            $sets[] = "$key = $param";
            $params[$param] = $value;
        }
        
        $query = "UPDATE {$this->table} SET " . implode(', ', $sets);
        
        if (!empty($this->where)) {
            $query .= ' WHERE ' . implode(' AND ', $this->where);
        }
        
        return [$query, array_merge($params, $this->params)];
    }

    public function getDeleteQuery()
    {
        $query = "DELETE FROM {$this->table}";
        
        if (!empty($this->where)) {
            $query .= ' WHERE ' . implode(' AND ', $this->where);
        }
        
        return [$query, $this->params];
    }
}
