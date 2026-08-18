<?php

namespace App\Model\Entity;

class Approvisionnement
{
    private string $referenceBl;
    private float $coutTotal;
    private \DateTime $dateAppro;
    private ?\DateTime $dateReception;
    private string $statut;

    private Fournisseur $fournisseur;
    private ?Utilisateur $utilisateur;

    private array $lignesApprovisionnement = [];

    public function __construct(
        string $referenceBl,
        Fournisseur $fournisseur,
        ?Utilisateur $utilisateur = null
    ) {
        $this->referenceBl = $referenceBl;
        $this->fournisseur = $fournisseur;
        $this->utilisateur = $utilisateur;

        $this->coutTotal = 0;
        $this->dateAppro = new \DateTime();
        $this->dateReception = null;
        $this->statut = 'EN_ATTENTE';
    }

    public function isReceived(): bool
    {
        return $this->statut === 'RECU';
    }

    public function calculateTotal(): float
    {
        $total = 0;

        foreach ($this->lignesApprovisionnement as $ligne) {
            $total += $ligne->calculateSubTotal();
        }

        return $total;
    }

    public function addLigne(
        LigneApprovisionnement $ligne
    ): void {
        $this->lignesApprovisionnement[] = $ligne;
    }

    public function updateStatut(): void
    {
        $this->coutTotal = $this->calculateTotal();
    }

    public function getLignesApprovisionnement(): array
    {
        return $this->lignesApprovisionnement;
    }

    public function getFournisseur(): Fournisseur
    {
        return $this->fournisseur;
    }
}