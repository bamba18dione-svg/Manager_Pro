<?php

namespace App\Model\Entity;

class Paiement
{
    private float $montant;
    private ?string $notes;
    private \DateTime $datePaiement;
    private string $reference;

    private Dette $dette;
    private ModePaiement $modePaiement;
    private ?Utilisateur $utilisateur;

    public function __construct(
        float $montant,
        Dette $dette,
        ModePaiement $modePaiement,
        string $reference,
        ?string $notes = null,
        ?Utilisateur $utilisateur = null
    ) {
        $this->montant = $montant;
        $this->dette = $dette;
        $this->modePaiement = $modePaiement;
        $this->reference = $reference;
        $this->notes = $notes;
        $this->utilisateur = $utilisateur;
        $this->datePaiement = new \DateTime();
    }

    public function getMontant(): float
    {
        return $this->montant;
    }

    public function getDette(): Dette
    {
        return $this->dette;
    }

    public function getModePaiement(): ModePaiement
    {
        return $this->modePaiement;
    }

    public function getReference(): string
    {
        return $this->reference;
    }
}