<?php
/**
 * Superstar Model - Pure PHP Version
 */

class Superstar extends Model {
    protected $table = 'superstars';
    protected $fillable = ['user_id', 'display_name', 'bio', 'price_per_hour', 'is_available', 'rating', 'total_followers', 'status', 'created_at', 'updated_at'];

    public function findByUserId($userId) {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ?";
        $result = $this->db->fetch($sql, [$userId]);
        return $result ? $this->mapToModel($result) : null;
    }

    public function getWithPagination($page = 1, $perPage = 15) {
        return $this->paginate($page, $perPage);
    }

    public function getAvailable() {
        $sql = "SELECT * FROM {$this->table} WHERE is_available = 1 ORDER BY rating DESC";
        $results = $this->db->fetchAll($sql);
        return array_map([$this, 'mapToModel'], $results);
    }

    public function toArray() {
        return [
            'id' => $this->id ?? null,
            'user_id' => $this->user_id ?? null,
            'display_name' => $this->display_name ?? null,
            'bio' => $this->bio ?? null,
            'price_per_hour' => $this->price_per_hour ?? null,
            'is_available' => $this->is_available ?? null,
            'rating' => $this->rating ?? null,
            'total_followers' => $this->total_followers ?? null,
            'status' => $this->status ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null
        ];
    }
}
