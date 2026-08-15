

<?php

namespace App\Model\Repository;

use App\Model\Entity\Produit;
use PDO;

class ProduitRepository extends BaseRepository
{
    public function getAllProduit(): array
    {
        $sql = "
            SELECT
                id,
                libelle,
                prix_vente,
                stock_initial
            FROM produits
            ORDER BY id DESC
        ";

        $stmt = $this->execute($sql);

        $produits = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produits[] = new Produit(
                $row['id'],
                $row['libelle'],
                (float) $row['prix_vente'],
                (int) $row['stock_initial']
            );
        }

        return $produits;
    }

       public function findProduitById(int $id): ?Produit
    {
        $sql = "
            SELECT
                id,
                libelle,
                prix_vente,
                stock_initial
            FROM produits
            WHERE id = :id
        ";

        $stmt = $this->execute($sql, [
            'id' => $id
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Produit(
            $row['id'],
            $row['libelle'],
            (float) $row['prix_vente'],
            (int) $row['stock_initial']
        );
    }
}


