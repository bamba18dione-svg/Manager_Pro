<?php

require_once dirname(__DIR__) .'/Repository/ProduitRepository.php';
require_once dirname(__DIR__) .'/Repository/ClientRepository.php';
require_once dirname(__DIR__) .'/Service/VenteService.php';


class POSController {

    private ProduitRepository $produitRepository;
    private ClientRepository $clientRepository;
    private VenteService $venteService;

    public function __construct()
    {
        $this->produitRepository = new ProduitRepository();
        $this->clientRepository = new ClientRepository();
        $this->venteService = new VenteService();
    }

      //Afficher la caisse
     
    public function index(): void
    {
        $produits = $this->produitRepository->getAllProduit();
        $clients = $this->clientRepository->getAllClients();

    }

      //Enregistrer une vente
     
    public function vendre(): void
    {
        $panier = $_POST['panier'] ?? [];

        $clientId = !empty($_POST['client_id'])
            ? (int) $_POST['client_id']
            : null;

        $montantVerse = (float) ($_POST['montant_verse'] ?? 0);

        try {

            $this->venteService->enregistrerVente($panier, $clientId, $montantVerse);

            $message = "Vente enregistrée avec succès.";

        } catch (\Exception $e) {

            $message = $e->getMessage();
        }

        // Recharger les données après la vente

        $produits = $this->produitRepository->getAllProduit();
        $clients = $this->clientRepository->getAllClients();

    }
}    