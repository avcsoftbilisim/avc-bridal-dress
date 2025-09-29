<?php
namespace App\Models;

use App\Core\Model;
use App\Core\DB;
use PDO;

class Product extends Model
{
    protected string $table = 'products';

    public function create(array $data): int
    {
        $cols = array_keys($data);
        $sql  = "INSERT INTO {$this->table} (".implode(',',$cols).") VALUES (".implode(',',array_fill(0,count($cols),'?')).")";
        $st = DB::conn()->prepare($sql);
        $st->execute(array_values($data));
        return (int)DB::conn()->lastInsertId();
    }

    public function all(int $limit = 100, int $offset = 0): array
    {
        $st = DB::conn()->prepare("SELECT * FROM {$this->table} ORDER BY id DESC LIMIT ?");
        $st->bindValue(1, $limit, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    // (İsterseniz gerçek tablolarla bağlayın)
    public function brands(): array     { return []; }
    public function categories(): array { return []; }

    // Yalnızca silinmeyenler (aktif)
    public function paginateActive(int $limit = 20, int $page = 1, ?string $q = null): array
    {
        $offset = max(0, ($page-1)*$limit);
        $params = [];
        $where  = "WHERE deleted_at IS NULL";

        if ($q) {
            $where .= " AND (name LIKE :q OR barcode LIKE :q)";
            $params[':q'] = "%{$q}%";
        }

        $total = (int) DB::conn()->prepare("SELECT COUNT(*) FROM {$this->table} {$where}")
                  ->execute($params) || true
                  ? (int) DB::conn()->prepare("SELECT COUNT(*) FROM {$this->table} {$where}")->fetchColumn()
                  : 0;

        $sql = "SELECT * FROM {$this->table} {$where} ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $st  = DB::conn()->prepare($sql);
        foreach ($params as $k=>$v) $st->bindValue($k, $v, PDO::PARAM_STR);
        $st->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $st->bindValue(':offset', $offset, PDO::PARAM_INT);
        $st->execute();

        return ['rows'=>$st->fetchAll(PDO::FETCH_ASSOC), 'total'=>$total, 'limit'=>$limit, 'page'=>$page];
    }

    // SİLİNENLER (deleted_at NOT NULL)
    public function paginateDeleted(int $limit = 20, int $page = 1, ?string $q = null): array
    {
        $offset = max(0, ($page-1)*$limit);
        $params = [];
        $where  = "WHERE deleted_at IS NOT NULL";

        if ($q) {
            $where .= " AND (name LIKE :q OR barcode LIKE :q)";
            $params[':q'] = "%{$q}%";
        }

        $stc = DB::conn()->prepare("SELECT COUNT(*) FROM {$this->table} {$where}");
        $stc->execute($params);
        $total = (int) $stc->fetchColumn();

        $sql = "SELECT * FROM {$this->table} {$where} ORDER BY deleted_at DESC, id DESC LIMIT :limit OFFSET :offset";
        $st  = DB::conn()->prepare($sql);
        foreach ($params as $k=>$v) $st->bindValue($k, $v, PDO::PARAM_STR);
        $st->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $st->bindValue(':offset', $offset, PDO::PARAM_INT);
        $st->execute();

        return ['rows'=>$st->fetchAll(PDO::FETCH_ASSOC), 'total'=>$total, 'limit'=>$limit, 'page'=>$page];
    }

    public function softDelete(int $id): bool
    {
        $st = DB::conn()->prepare("UPDATE {$this->table} SET deleted_at = NOW() WHERE id = ?");
        return $st->execute([$id]);
    }

    public function restore(int $id): bool
    {
        $st = DB::conn()->prepare("UPDATE {$this->table} SET deleted_at = NULL WHERE id = ?");
        return $st->execute([$id]);
    }

    public function purge(int $id): bool
    {
        $st = DB::conn()->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $st->execute([$id]);
    }
}