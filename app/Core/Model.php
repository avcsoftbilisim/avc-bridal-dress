<?php
namespace App\Core;
use App\Core\DB;
use PDO;

abstract class Model {
    // Alt sınıfın SET ETMESİ GEREKİR; boş bırakılırsa anlamlı hata verelim
    protected string $table = '';
    protected string $primaryKey = 'id';

    protected function ensureTable(): void {
        if ($this->table === '') {
            throw new \RuntimeException(static::class."::\$table is empty. Set protected string \$table = 'table_name';");
        }
    }

    // Listeleme: limit/offset destekli (alt sınıf daha fazla param eklemesin diye burada tanımlıyoruz)
    public function all(int $limit = 100, int $offset = 0): array {
        $this->ensureTable();
        $sql = "SELECT * FROM `{$this->table}` ORDER BY `{$this->primaryKey}` DESC LIMIT :limit OFFSET :offset";
        $stmt = DB::conn()->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tek kayıt: imza ÜST SINIFTA bu — alt sınıf AYNI imzayı kullanmalı
    public function find($id): ?array {
        $this->ensureTable();
        $stmt = DB::conn()->prepare("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id LIMIT 1");
        // PK'nız int olduğu için güvenli biçimde int'e çeviriyoruz
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
