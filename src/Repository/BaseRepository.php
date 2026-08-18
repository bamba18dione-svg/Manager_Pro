

<?php

namespace App\Model\Repository;

use App\Core\Database;
use PDO;

class BaseRepository
{
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    protected function execute(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }
}