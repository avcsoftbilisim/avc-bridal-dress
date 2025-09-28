<?php
namespace App\Models;
use App\Core\Model;
use App\Core\DB;
use PDO;
class Customer extends Model {
    // TİPİYLE BİRLİKTE TANIMLAYIN (static değil!)
    protected string $table = 'customers';

    public static function sanitizePhone(?string $v): string {
        return preg_replace('/\D+/', '', (string)$v ?? '');
    }

    public function create(array $data): int {
        $cols = array_keys($data);
        $placeholders = implode(',', array_fill(0, count($cols), '?'));
        $sql  = "INSERT INTO `{$this->table}` (".implode(',', $cols).") VALUES ($placeholders)";
        $stmt = DB::conn()->prepare($sql);
        $stmt->execute(array_values($data));
        return (int) DB::conn()->lastInsertId();
    }

    public function updateById(int $id, array $data): bool {
        $sets = implode(',', array_map(fn($k)=>"`$k` = ?", array_keys($data)));
        $sql  = "UPDATE `{$this->table}` SET $sets WHERE `{$this->primaryKey}` = ?";
        $stmt = DB::conn()->prepare($sql);
        return $stmt->execute([...array_values($data), $id]);
    }

}
