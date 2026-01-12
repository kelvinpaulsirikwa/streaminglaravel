<?php
/**
 * User Model - Pure PHP Version
 */

class User extends Model {
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'username', 'password', 'role', 'is_verified', 'is_blocked', 'profile_image', 'bio', 'created_at', 'updated_at'];

    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        $result = $this->db->fetch($sql, [$email]);
        return $result ? $this->mapToModel($result) : null;
    }

    public function createGoogleUser($data) {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'] ?? $data['email'],
            'password' => password_hash($data['password'] ?? 'password123', PASSWORD_DEFAULT),
            'role' => 'user',
            'is_verified' => true,
            'is_blocked' => false,
            'profile_image' => $data['image'] ?? null,
            'bio' => $data['bio'] ?? null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $this->create($userData);
    }

    protected function mapToModel($data) {
        $model = new static();
        foreach ($data as $key => $value) {
            $model->$key = $value;
        }
        return $model;
    }

    public function toArray() {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'email' => $this->email ?? null,
            'username' => $this->username ?? null,
            'image' => $this->profile_image ?? null,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null
        ];
    }
}
