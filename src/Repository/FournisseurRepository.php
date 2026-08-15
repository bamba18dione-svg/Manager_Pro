
<?php

namespace App\Model\Repository;

use App\Model\Entity\Fournisseur;
use PDO;

class FournisseurRepository extends BaseRepository
{
    public function getAllFournisseurs(): array
    {
        $sql = "
            SELECT
                id,
                nom,
                email,
                telephone,
                adresse
            FROM fournisseurs
            ORDER BY id DESC
        ";

        $stmt = $this->execute($sql);

        $fournisseurs = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $fournisseurs[] = new Fournisseur(
                $row['id'],
                $row['nom'],
                $row['email'],
                $row['telephone'],
                $row['adresse']
            );
        }

        return $fournisseurs;
    }

    public function findFournisseurById(int $id): ?Fournisseur
    {
        $sql = "
            SELECT
                id,
                nom,
                email,
                telephone,
                adresse
            FROM fournisseurs
            WHERE id = :id
        ";

        $stmt = $this->execute($sql, [
            'id' => $id
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Fournisseur(
            $row['id'],
            $row['nom'],
            $row['email'],
            $row['telephone'],
            $row['adresse']
        );
    }
}