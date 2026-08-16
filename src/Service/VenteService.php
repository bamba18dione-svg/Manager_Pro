<?php

namespace App\Service;

use App\Core\Database;
use App\Model\Repository\ProduitRepository;
use App\Model\Repository\ClientRepository;
use PDO;
use Exception;

class VenteService
{
    private PDO $pdo;
    private ProduitRepository $produitRepository;
    private ClientRepository $clientRepository;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();

        $this->produitRepository = new ProduitRepository();
        $this->clientRepository = new ClientRepository();
    }

    public function calculerTotal(array $panier): float
    {
        $total = 0;

        foreach ($panier as $ligne) {
            $total += $ligne['prix'] * $ligne['quantite'];
        }

        return $total;
    }

    private function verifierStock(array $panier): void
    {
        foreach ($panier as $ligne) {

            $produit = $this->produitRepository
                ->findProduitById($ligne['produit_id']);

            if ($produit === null) {
                throw new Exception(
                    "Produit introuvable : " . $ligne['produit_id']
                );
            }

            if ($produit->getStockActuel() < $ligne['quantite']) {
                throw new Exception(
                    "Stock insuffisant pour le produit : "
                    . $produit->getLibelle()
                );
            }
        }
    }

    private function verifierCredit(
        int $clientId,
        float $montant
    ): void {

        $client = $this->clientRepository
            ->findClientById($clientId);

        if ($client === null) {
            throw new Exception("Client introuvable.");
        }

        if (!$client->canAfford($montant)) {
            throw new Exception(
                "La limite de crédit du client est dépassée."
            );
        }
    }

    private function diminuerStock(array $panier): void
    {
        foreach ($panier as $ligne) {

            $sql = "
                UPDATE produits
                SET stock_actuel = stock_actuel - :quantite
                WHERE id = :id
            ";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                'quantite' => $ligne['quantite'],
                'id' => $ligne['produit_id']
            ]);
        }
    }

    public function enregistrerVente(array $panier, ?int $clientId, float $montantVerse): bool {

        if (empty($panier)) {
            throw new Exception("Le panier est vide.");
        }

        $total = $this->calculerTotal($panier);

        try {
            $this->pdo->beginTransaction();

            $this->verifierStock($panier);

            if ($clientId !== null) {
                $reste = $total - $montantVerse;

                if ($reste > 0) {
                    $this->verifierCredit($clientId, $reste);
                }
            }

            $this->diminuerStock($panier);

             // 4. Créer la vente
            $sql = "
                INSERT INTO ventes(numero_facture, montant_total, montant_verse, statut, utilisateur_id, client_id)

                VALUES (:numero_facture, :montant_total, :montant_verse, :statut, :utilisateur_id, :client_id)  
            ";

            $stmt = $this->pdo->prepare($sql);

            $numeroFacture = "FAC-" . time();

            $statut = $montantVerse >= $total
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

            $venteId = $this->pdo->lastInsertId();

            // 5. Enregistrer les lignes de vente
            foreach ($panier as $ligne) {

                $sql = "
                    INSERT INTO lignes_vente(vente_id, produit_id, quantite, prix_unitaire, sous_total)

                    VALUES(:vente_id, :produit_id, :quantite, :prix_unitaire,  :sous_total )       
                ";

                $stmt = $this->pdo->prepare($sql);

                $sousTotal =
                    $ligne['prix'] * $ligne['quantite'];

                $stmt->execute([
                    'vente_id' => $venteId,
                    'produit_id' => $ligne['produit_id'],
                    'quantite' => $ligne['quantite'],
                    'prix_unitaire' => $ligne['prix'],
                    'sous_total' => $sousTotal
                ]);
            }

            $this->pdo->commit();

            return true;

        } catch (Exception $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }
}