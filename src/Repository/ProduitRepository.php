<?php

namespace App\Model\Repository;

use App\Core\Database;
use App\Model\Entity\Produit;
use PDO;

class ProduitRepository
{
    private function __construct()
    {
    }

    public static function getAllProduit(): array
    {
        $pdo = Database::getConnection();

        $sql = "
            SELECT
                code,
                libelle,
                categorie,
                prix_vente,
                cout_achat,
                stock_initial,
                stock_actuel,
                seuil_alerte
            FROM produits
            ORDER BY id DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $produits = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $produits[] = new Produit(
                $row['code'],
                $row['libelle'],
                $row['categorie'] ?? '',
                (float) $row['prix_vente'],
                (float) $row['cout_achat'],
                (int) $row['stock_initial'],
                (int) $row['stock_actuel'],
                (int) $row['seuil_alerte']
            );
        }

        return $produits;
    }

    public static function findProduitById(int $id): ?Produit
    {
        $pdo = Database::getConnection();

        $sql = "
            SELECT
                code,
                libelle,
                categorie,
                prix_vente,
                cout_achat,
                stock_initial,
                stock_actuel,
                seuil_alerte
            FROM produits
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

        return new Produit(
            $row['code'],
            $row['libelle'],
            $row['categorie'] ?? '',
            (float) $row['prix_vente'],
            (float) $row['cout_achat'],
            (int) $row['stock_initial'],
            (int) $row['stock_actuel'],
            (int) $row['seuil_alerte']
        );
    }
}