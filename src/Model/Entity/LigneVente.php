
<?php


class LigneVente
{
    private int $id;
    private int $quantite;
    private float $prixUnitaire;
    private float $sousTotal;

    private Produit $produit;
    private Vente $vente;

    public function __construct(
        int $id,
        Produit $produit,
        int $quantite,
        float $prixUnitaire,
        float $sousTotal = 0,
        ?Vente $vente = null
    ) {
        $this->id = $id;
        $this->produit = $produit;
        $this->quantite = $quantite;
        $this->prixUnitaire = $prixUnitaire;
        $this->sousTotal = $sousTotal;
        $this->vente = $vente;
    }

    public function calculateSubTotal(): float
    {
        return $this->quantite * $this->prixUnitaire;
    }

    public function getProduit(): Produit
    {
        return $this->produit;
    }
}