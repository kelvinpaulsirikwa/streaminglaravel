<?php
/**
 * Model Base Class - Pure PHP Version
 */

class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $result = $this->db->fetch($sql, [$id]);
        return $result ? $this->mapToModel($result) : null;
    }

    public function findOrFail($id) {
        $result = $this->find($id);
        if (!$result) {
            Response::notFound("Resource not found");
        }
        return $result;
    }

    public function where($column, $operator, $value = null) {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} ?";
        $results = $this->db->fetchAll($sql, [$value]);
        
        return array_map([$this, 'mapToModel'], $results);
    }

    public function create($data) {
        $filteredData = [];
        foreach ($this->fillable as $field) {
            if (isset($data[$field])) {
                $filteredData[$field] = $data[$field];
            }
        }
        
        $id = $this->db->insert($this->table, $filteredData);
        return $this->find($id);
    }

    public function update($id, $data) {
        $filteredData = [];
        foreach ($this->fillable as $field) {
            if (isset($data[$field])) {
                $filteredData[$field] = $data[$field];
            }
        }
        
        return $this->db->update($this->table, $filteredData, "{$this->primaryKey} = ?", [$id]);
    }

    public function delete($id) {
        return $this->db->delete($this->table, "{$this->primaryKey} = ?", [$id]);
    }

    public function all($limit = null, $orderBy = 'created_at DESC') {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $results = $this->db->fetchAll($sql);
        return array_map([$this, 'mapToModel'], $results);
    }

    public function paginate($page = 1, $perPage = 15, $orderBy = 'created_at DESC') {
        $offset = ($page - 1) * $perPage;
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        $totalResult = $this->db->fetch($countSql);
        $total = $totalResult['total'];
        
        // Get data
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}";
        $results = $this->db->fetchAll($sql);
        
        $data = array_map([$this, 'mapToModel'], $results);
        
        return [
            'data' => $data,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'per_page' => $perPage,
            'total' => $total,
            'from' => $offset + 1,
            'to' => $offset + count($data),
            'has_more_pages' => $page < ceil($total / $perPage)
        ];
    }

    protected function mapToModel($data) {
        $model = new static();
        foreach ($data as $key => $value) {
            if (property_exists($model, $key)) {
                $model->$key = $value;
            }
        }
        return $model;
    }
}
