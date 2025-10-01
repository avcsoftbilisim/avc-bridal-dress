<?php
namespace App\Models;

use App\Core\Model;
use App\Core\DB;
use PDO;

class TailorJob extends Model
{
    protected string $table = 'tailor_jobs';

    public function store(array $d): int {
        $sql = "INSERT INTO {$this->table}
          (tailor_id, product_id, product_name, note, price, sent_at, due_at)
          VALUES(?,?,?,?,?, NOW(), ?)";
        $st  = DB::conn()->prepare($sql);
        $st->execute([
          (int)$d['tailor_id'],
          $d['product_id'] ? (int)$d['product_id'] : null,
          trim($d['product_name']),
          $d['note'] ?? null,
          $d['price'] ?? null,
          $d['due_at'] ?? null
        ]);
        return (int)DB::conn()->lastInsertId();
    }

    /** Listeleme + sayfalama */
    public function paginate(string $scope, int $limit=20, int $page=1, ?string $q=null): array {
        $offset = max(0, ($page-1)*$limit);
        $where  = "tj.deleted_at IS NULL";

        switch ($scope) {
            case 'past':
                $where .= " AND tj.returned_at IS NOT NULL";
                break;
            case 'future':
                $where .= " AND tj.sent_at > NOW()";
                break;
            default: // current
                $where .= " AND tj.returned_at IS NULL AND tj.sent_at <= NOW()";
        }
        $bind = [];
        if ($q) {
            $where .= " AND (t.name LIKE :q OR tj.product_name LIKE :q)";
            $bind[':q'] = "%$q%";
        }

        $sql = "FROM tailor_jobs tj
                JOIN tailors t ON t.id = tj.tailor_id
                WHERE $where";

        $cnt = DB::conn()->prepare("SELECT COUNT(*) ".$sql);
        $cnt->execute($bind);
        $total = (int)$cnt->fetchColumn();

        $st = DB::conn()->prepare("
          SELECT tj.*, t.name AS tailor_name, t.phone AS tailor_phone
          ".$sql."
          ORDER BY tj.sent_at DESC
          LIMIT $limit OFFSET $offset
        ");
        $st->execute($bind);
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);

        return ['rows'=>$rows,'total'=>$total,'page'=>$page,'limit'=>$limit];
    }

    public function markReturned(int $id): bool {
        $st = DB::conn()->prepare("UPDATE {$this->table} SET returned_at=NOW() WHERE id=?");
        return $st->execute([$id]);
    }
}