<?php

namespace App\Model\Entity;

class Dette
{
    private string $ref;
    private float $montantInitial;
    private float $montantVerse;
    private float $montantRestant;
    private \DateTime $dateEcheance;
    private string $statut;

    private Client $client;
    private ?Vente $vente;

    public function __construct(
        string $ref,
        float $montantInitial,
        Client $client,
        ?Vente $vente = null,
        ?\DateTime $dateEcheance = null
    ) {
        $this->ref = $ref;
        $this->montantInitial = $montantInitial;
        $this->montantVerse = 0;
        $this->montantRestant = $montantInitial;
        $this->client = $client;
        $this->vente = $vente;
        $this->dateEcheance = $dateEcheance ?? new \DateTime();
        $this->statut = 'EN_COURS';
    }

    public function getResteDu(): float
    {
        return $this->montantRestant;
    }

    public function isSold(): bool
    {
        return $this->montantRestant <= 0;
    }

    public function applyPayment(float $montant): void
    {
        if ($montant <= 0) {
            throw new \Exception("Le paiement doit être positif.");
        }

        if ($montant > $this->montantRestant) {
            throw new \Exception(
                "Le paiement dépasse le montant restant."
            );
        }

        $this->montantVerse += $montant;
        $this->montantRestant -= $montant;

        $this->updateStatut();
    }

    public function updateStatut(): void
    {
        $this->statut = $this->isSold()
            ? 'SOLDEE'
            : 'EN_COURS';
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getVente(): ?Vente
    {
        return $this->vente;
    }
}