<?php

namespace App\Model\Entity;

class LigneApprovisionnement
{
    private int $quantiteAppro;
    private int $quantiteRecue;
    private float $prixAchat;
    private Produit $produit;

    public function __construct(
        Produit $produit,
        int $quantiteAppro,
        int $quantiteRecue,
        float $prixAchat
    ) {
        $this->produit = $produit;
        $this->quantiteAppro = $quantiteAppro;
        $this->quantiteRecue = $quantiteRecue;
        $this->prixAchat = $prixAchat;
    }

    public function getQuantiteManquante(): int
    {
        return $this->quantiteAppro - $this->quantiteRecue;
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