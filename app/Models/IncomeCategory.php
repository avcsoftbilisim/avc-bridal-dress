<?php
namespace App\Models;

use App\Core\Model;
use App\Core\DB;
use PDO;

class IncomeCategory extends Model
{
    protected string $table = 'income_categories';

    public function listAll(): array
    {
        $sql = "SELECT id, title, sort
                FROM {$this->table}
                WHERE deleted_at IS NULL
                ORDER BY sort, title";
        return DB::conn()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = DB::conn()->prepare(
            "INSERT INTO {$this->table} (title, sort) VALUES (?, ?)"
        );
        $stmt->execute([trim($data['title']), (int)($data['sort'] ?? 0)]);
        return (int)DB::conn()->lastInsertId();
    }

    public function updateById(int $id, array $data): bool
    {
        $stmt = DB::conn()->prepare(
            "UPDATE {$this->table} SET title=?, sort=? WHERE id=? AND deleted_at IS NULL"
        );
        return $stmt->execute([trim($data['title']), (int)($data['sort'] ?? 0), $id]);
    }

    public function softDelete(int $id): bool
    {
        $stmt = DB::conn()->prepare(
            "UPDATE {$this->table} SET deleted_at=NOW() WHERE id=? AND deleted_at IS NULL"
        );
        return $stmt->execute([$id]);
    }
}