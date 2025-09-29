<?php
namespace App\Models;

use App\Core\Model;
use App\Core\DB;
use PDO;

class IncomingProduct extends Model
{
    protected string $table = 'incoming_products';

    public function create(array $data): int
    {
        $cols = array_keys($data);
        $sql  = "INSERT INTO {$this->table} (".implode(',', $cols).") VALUES (".implode(',', array_fill(0,count($cols),'?')).")";
        $st   = DB::conn()->prepare($sql);
        $st->execute(array_values($data));
        return (int) DB::conn()->lastInsertId();
    }

    public function paginate(int $limit = 20, int $page = 1): array
    {
        $offset = max(0, ($page-1)*$limit);

        $total = (int) DB::conn()->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();

        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $st  = DB::conn()->prepare($sql);
        $st->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $st->bindValue(':offset', $offset, PDO::PARAM_INT);
        $st->execute();
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);

        return ['rows'=>$rows, 'total'=>$total, 'limit'=>$limit, 'page'=>$page];
    }
}