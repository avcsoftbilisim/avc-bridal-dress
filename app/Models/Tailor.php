<?php
namespace App\Models;

use App\Core\Model;
use App\Core\DB;
use PDO;

class Tailor extends Model
{
    protected string $table = 'tailors';

    public function create(array $d): int {
        $sql = "INSERT INTO {$this->table}(name,phone,phone2,address) VALUES(?,?,?,?)";
        $st  = DB::conn()->prepare($sql);
        $st->execute([$d['name'],$d['phone']??null,$d['phone2']??null,$d['address']??null]);
        return (int)DB::conn()->lastInsertId();
    }

    public function options(): array {
        $st = DB::conn()->query("SELECT id, name, phone FROM {$this->table} WHERE deleted_at IS NULL ORDER BY name");
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}