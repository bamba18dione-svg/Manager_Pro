<?php

namespace App\Model\Repository;

use App\Core\Database;
use App\Model\Entity\Client;
use PDO;

class ClientRepository
{
    private function __construct()
    {
    }

    public static function getAllClients(): array
    {
        $pdo = Database::getConnection();

        $sql = "
            SELECT
                nom,
                prenom,
                telephone,
                email,
                limite_credit,
                solde_actuel
            FROM clients
            ORDER BY id DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $clients = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $clients[] = new Client(
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

    public static function findClientById(int $id): ?Client
    {
        $pdo = Database::getConnection();

        $sql = "
            SELECT
                nom,
                prenom,
                telephone,
                email,
                limite_credit,
                solde_actuel
            FROM clients
            WHERE id = :id
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Client(
            $row['nom'],
            $row['prenom'],
            $row['telephone'],
            $row['email'],
            (float) $row['limite_credit'],
            (float) $row['solde_actuel']
        );
    }
}