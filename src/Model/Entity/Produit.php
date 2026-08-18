<?php

namespace App\Model\Entity;

class Produit
{
    private string $code;
    private string $libelle;
    private string $categorie;
    private float $prixVente;
    private float $coutAchat;
    private int $stockInitial;
    private int $stockActuel;
    private int $seuilAlerte;
    private ?Fournisseur $fournisseur;

    public function __construct(
        string $code,
        string $libelle,
        string $categorie,
        float $prixVente,
        float $coutAchat,
        int $stockInitial,
        int $stockActuel,
        int $seuilAlerte,
        ?Fournisseur $fournisseur = null
    ) {
        $this->code = $code;
        $this->libelle = $libelle;
        $this->categorie = $categorie;
        $this->prixVente = $prixVente;
        $this->coutAchat = $coutAchat;
        $this->stockInitial = $stockInitial;
        $this->stockActuel = $stockActuel;
        $this->seuilAlerte = $seuilAlerte;
        $this->fournisseur = $fournisseur;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function getPrixVente(): float
    {
        return $this->prixVente;
    }

    public function getCoutAchat(): float
    {
        return $this->coutAchat;
    }

    public function getStockInitial(): int
    {
        return $this->stockInitial;
    }

    public function getStockActuel(): int
    {
        return $this->stockActuel;
    }

    public function getSeuilAlerte(): int
    {
        return $this->seuilAlerte;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function updateStock(int $quantite): void
    {
        $nouveauStock = $this->stockActuel + $quantite;

        if ($nouveauStock < 0) {
            throw new \Exception("Le stock ne peut pas être négatif.");
        }

        $this->stockActuel = $nouveauStock;
    }

    public function isStockLow(): bool
    {
        return $this->stockActuel <= $this->seuilAlerte;
    }

    public function getMarge(): float
    {
        return $this->prixVente - $this->coutAchat;
    }

    public function getTauxMarge(): float
    {
        if ($this->coutAchat == 0) {
            return 0;
        }

        return ($this->getMarge() / $this->coutAchat) * 100;
    }
}