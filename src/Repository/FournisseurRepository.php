<?php

namespace App\Model\Repository;

use App\Core\Database;
use App\Model\Entity\Fournisseur;
use PDO;

class FournisseurRepository
{
    private function __construct()
    {
    }

    public static function getAllFournisseurs(): array
    {
        $pdo = Database::getConnection();

        $sql = "
            SELECT
                nom,
                email,
                telephone,
                adresse
            FROM fournisseurs
            ORDER BY id DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $fournisseurs = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $fournisseurs[] = new Fournisseur(
                $row['nom'],
                $row['email'],
                $row['telephone'],
                $row['adresse']
            );
        }

        return $fournisseurs;
    }

    public static function findFournisseurById(
        int $id
    ): ?Fournisseur {

        $pdo = Database::getConnection();

        $sql = "
            SELECT
                nom,
                email,
                telephone,
                adresse
            FROM fournisseurs
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

        return new Fournisseur(
            $row['nom'],
            $row['email'],
            $row['telephone'],
            $row['adresse']
        );
    }
}