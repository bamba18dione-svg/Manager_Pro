
<?php



class LigneApprovisionnement
{
    private int $id;
    private int $quantiteAppro;
    private int $quantiteRecue;
    private float $prixAchat;
    private float $sousTotal;

    private Produit $produit;
    private Approvisionnement $approvisionnement;

    public function __construct(
        int $id,
        Produit $produit,
        int $quantiteAppro,
        int $quantiteRecue,
        float $prixAchat,
        float $sousTotal = 0,
        ?Approvisionnement $approvisionnement = null
    ) {
        $this->id = $id;
        $this->produit = $produit;
        $this->quantiteAppro = $quantiteAppro;
        $this->quantiteRecue = $quantiteRecue;
        $this->prixAchat = $prixAchat;
        $this->sousTotal = $sousTotal;
        $this->approvisionnement = $approvisionnement;
    }

    public function getQuantiteManquante(): int
    {
        return max(0, $this->quantiteAppro - $this->quantiteRecue);
    }

    public function calculateSubTotal(): float
    {
        return $this->quantiteAppro * $this->prixAchat;
    }

    public function getProduit(): Produit
    {
        return $this->produit;
    }
}