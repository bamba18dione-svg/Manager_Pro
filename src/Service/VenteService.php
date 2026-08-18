<?php

namespace App\Service;

use App\Core\Database;
use App\Model\Repository\ProduitRepository;
use App\Model\Repository\ClientRepository;
use PDO;
use Exception;

class VenteService
{
    private function __construct()
    {
    }

    public static function calculerTotal(array $panier): float
    {
        $total = 0;

        foreach ($panier as $ligne) {
            $total +=
                $ligne['prix'] *
                $ligne['quantite'];
        }

        return $total;
    }

    private static function verifierStock(
        array $panier
    ): void {

        foreach ($panier as $ligne) {

            $produit =
                ProduitRepository::findProduitById(
                    $ligne['produit_id']
                );

            if ($produit === null) {
                throw new Exception(
                    "Produit introuvable."
                );
            }

            if (
                $produit->getStockActuel()
                < $ligne['quantite']
            ) {
                throw new Exception(
                    "Stock insuffisant pour : "
                    . $produit->getLibelle()
                );
            }
        }
    }

    private static function verifierCredit(
        int $clientId,
        float $montant
    ): void {

        $client =
            ClientRepository::findClientById(
                $clientId
            );

        if ($client === null) {
            throw new Exception(
                "Client introuvable."
            );
        }

        if (!$client->canAfford($montant)) {
            throw new Exception(
                "Limite de crédit dépassée."
            );
        }
    }

    private static function diminuerStock(
        array $panier
    ): void {

        $pdo = Database::getConnection();

        foreach ($panier as $ligne) {

            $sql = "
                UPDATE produits
                SET stock_actuel =
                    stock_actuel - :quantite
                WHERE id = :id
            ";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                'quantite' => $ligne['quantite'],
                'id' => $ligne['produit_id']
            ]);
        }
    }

    public static function enregistrerVente(
        array $panier,
        ?int $clientId,
        float $montantVerse
    ): bool {

        if (empty($panier)) {
            throw new Exception(
                "Le panier est vide."
            );
        }

        $pdo = Database::getConnection();

        $total = self::calculerTotal($panier);

        try {

            $pdo->beginTransaction();

            self::verifierStock($panier);

            if ($clientId !== null) {

                $reste =
                    $total - $montantVerse;

                if ($reste > 0) {
                    self::verifierCredit(
                        $clientId,
                        $reste
                    );
                }
            }

            self::diminuerStock($panier);

            $sql = "
                INSERT INTO ventes
                (
                    numero_facture,
                    montant_total,
                    montant_verse,
                    statut,
                    utilisateur_id,
                    client_id
                )
                VALUES
                (
                    :numero_facture,
                    :montant_total,
                    :montant_verse,
                    :statut,
                    :utilisateur_id,
                    :client_id
                )
            ";

            $stmt = $pdo->prepare($sql);

            $numeroFacture =
                "FAC-" . time();

            $statut =
                $montantVerse >= $total
                    ? "PAYEE"
                    : "CREDIT";

            $stmt->execute([
                'numero_facture' => $numeroFacture,
                'montant_total' => $total,
                'montant_verse' => $montantVerse,
                'statut' => $statut,
                'utilisateur_id' => 1,
                'client_id' => $clientId
            ]);

            $venteId =
                $pdo->lastInsertId();

            foreach ($panier as $ligne) {

                $sousTotal =
                    $ligne['prix']
                    * $ligne['quantite'];

                $sql = "
                    INSERT INTO lignes_vente
                    (
                        vente_id,
                        produit_id,
                        quantite,
                        prix_unitaire,
                        sous_total
                    )
                    VALUES
                    (
                        :vente_id,
                        :produit_id,
                        :quantite,
                        :prix_unitaire,
                        :sous_total
                    )
                ";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    'vente_id' => $venteId,
                    'produit_id' => $ligne['produit_id'],
                    'quantite' => $ligne['quantite'],
                    'prix_unitaire' => $ligne['prix'],
                    'sous_total' => $sousTotal
                ]);
            }

            $pdo->commit();

            return true;

        } catch (Exception $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }
    }
}