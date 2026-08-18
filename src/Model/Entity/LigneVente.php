<?php

namespace App\Model\Entity;

class LigneVente
{
    private int $quantite;
    private float $prixUnitaire;
    private Produit $produit;

    public function __construct(
        Produit $produit,
        int $quantite,
        float $prixUnitaire
    ) {
        $this->produit = $produit;
        $this->quantite = $quantite;
        $this->prixUnitaire = $prixUnitaire;
    }

    public function calculateSubTotal(): float
    {
        return $this->quantite * $this->prixUnitaire;
    }

    public function getProduit(): Produit
    {
        return $this->produit;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function getPrixUnitaire(): float
    {
        return $this->prixUnitaire;
    }
}