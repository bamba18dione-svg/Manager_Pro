
<?php

namespace App\Model\Repository;

use App\Model\Entity\Client;
use PDO;

class ClientRepository extends BaseRepository
{
    public function getAllClients(): array
    {
        $sql = "
            SELECT
                id,
                nom,
                prenom,
                telephone,
                email,
                limite_credit,
                solde_actuel
            FROM clients
            ORDER BY id DESC
        ";

        $stmt = $this->execute($sql);

        $clients = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $clients[] = new Client(
                $row['id'],
                $row['nom'],
                $row['prenom'],
                $row['telephone'],
                $row['email'],
                (float) $row['limite_credit'],
                (float) $row['solde_actuel']
            );
        }

        return $clients;
    }

    public function findClientById(int $id): ?Client
    {
        $sql = "
            SELECT
                id,
                nom,
                prenom,
                telephone,
                email,
                limite_credit,
                solde_actuel
            FROM clients
            WHERE id = :id
        ";

        $stmt = $this->execute($sql, [
            'id' => $id
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Client(
            $row['id'],
            $row['nom'],
            $row['prenom'],
            $row['telephone'],
            $row['email'],
            (float) $row['limite_credit'],
            (float) $row['solde_actuel']
        );
    }
}

