<?php
namespace App\Core;
use App\Core\DB;
use PDO;

abstract class Model {
    protected string $table;
    protected string $primaryKey = 'id';

    public function all(): array {
        $stmt = DB::conn()->query("SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} DESC");
        return $stmt->fetchAll();
    }

    public function find($id): ?array {
        $stmt = DB::conn()->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
