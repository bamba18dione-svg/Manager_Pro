<?php

require_once dirname(__DIR__) .'/Repository/ProduitRepository.php';
require_once dirname(__DIR__) .'/Repository/ClientRepository.php';
require_once dirname(__DIR__) .'/Service/VenteService.php';



class POSController
{
    private function __construct()
    {
    }

    public static function index(): void
    {
        $produits =
            ProduitRepository::getAllProduit();

        $clients =
            ClientRepository::getAllClients();

       
    }

    public static function vendre(): void
    {
        $panier =
            $_POST['panier'] ?? [];

        $clientId =
            !empty($_POST['client_id'])
                ? (int) $_POST['client_id']
                : null;

        $montantVerse =
            (float) ($_POST['montant_verse'] ?? 0);

        try {

            VenteService::enregistrerVente(
                $panier,
                $clientId,
                $montantVerse
            );

            $message =
                "Vente enregistrée avec succès.";

        } catch (\Exception $e) {

            $message =
                $e->getMessage();
        }

        $produits =
            ProduitRepository::getAllProduit();

        $clients =
            ClientRepository::getAllClients();

        
            
    }
} 