<?php
namespace App\Models;

use App\Core\Model;
use App\Core\DB;
use PDO;

class Rental extends Model
{
    protected string $table = 'rentals';

    /**
     * $scope: 'ongoing' | 'future' | 'past'
     */
    public function paginate(string $scope='ongoing', int $limit=20, int $page=1, ?string $q=null): array
    {
        $offset = max(0, ($page-1)*$limit);
        $params = [];
        $where  = "r.deleted_at IS NULL";
        $today  = date('Y-m-d');

        switch ($scope) {
            case 'future':
                $where .= " AND r.start_date > :today AND r.return_date IS NULL";
                $params[':today'] = $today;
                break;
            case 'past':
                $where .= " AND r.return_date IS NOT NULL";
                break;
            default: // ongoing
                $where .= " AND r.start_date <= :today AND r.return_date IS NULL";
                $params[':today'] = $today;
        }

        if ($q) {
            $where .= " AND (c.name LIKE :q OR c.phone LIKE :q OR p.name LIKE :q OR p.barcode LIKE :q)";
            $params[':q'] = "%{$q}%";
        }

        // count
        $stc = DB::conn()->prepare("
            SELECT COUNT(*)
            FROM rentals r
              JOIN customers c ON c.id = r.customer_id
              JOIN products  p ON p.id = r.product_id
            WHERE {$where}
        ");
        $stc->execute($params);
        $total = (int)$stc->fetchColumn();

        // rows
        $sql = "
            SELECT r.*, c.name AS customer_name, c.phone,
                   p.name AS product_name, p.barcode
            FROM rentals r
              JOIN customers c ON c.id = r.customer_id
              JOIN products  p ON p.id = r.product_id
            WHERE {$where}
            ORDER BY r.start_date DESC, r.id DESC
            LIMIT :limit OFFSET :offset
        ";
        $st = DB::conn()->prepare($sql);
        foreach ($params as $k=>$v) {
            $st->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $st->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $st->bindValue(':offset', $offset, PDO::PARAM_INT);
        $st->execute();

        return [
            'rows'  => $st->fetchAll(PDO::FETCH_ASSOC),
            'total' => $total,
            'limit' => $limit,
            'page'  => $page
        ];
    }

    public function markReturned(int $id, ?string $date=null): bool
    {
        $date = $date ?: date('Y-m-d');
        $st = DB::conn()->prepare("UPDATE {$this->table} SET return_date = :d WHERE id = :id");
        return $st->execute([':d'=>$date, ':id'=>$id]);
    }
}