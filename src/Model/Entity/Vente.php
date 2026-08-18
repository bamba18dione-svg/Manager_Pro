<?php

namespace App\Model\Entity;

class Vente
{
    private string $numeroFacture;
    private float $montantTotal;
    private float $montantVerse;
    private string $modeReglement;
    private string $statut;

    private \DateTime $dateVente;
    private ?\DateTime $dateEcheance;

    private Utilisateur $utilisateur;
    private ?Client $client;

    private array $lignesVente = [];

    public function __construct(
        string $numeroFacture,
        float $montantTotal,
        float $montantVerse,
        string $modeReglement,
        Utilisateur $utilisateur,
        ?Client $client = null
    ) {
        $this->numeroFacture = $numeroFacture;
        $this->montantTotal = $montantTotal;
        $this->montantVerse = $montantVerse;
        $this->modeReglement = $modeReglement;
        $this->utilisateur = $utilisateur;
        $this->client = $client;

        $this->dateVente = new \DateTime();

        $this->statut =
            $montantVerse >= $montantTotal
                ? 'PAYEE'
                : 'CREDIT';
    }

    public function getRemainingAmount(): float
    {
        return $this->montantTotal - $this->montantVerse;
    }

    public function isFullyPaid(): bool
    {
        return $this->getRemainingAmount() <= 0;
    }

    public function updateStatus(): void
    {
        $this->statut = $this->isFullyPaid()
            ? 'PAYEE'
            : 'CREDIT';
    }

    public function addLigne(LigneVente $ligne): void
    {
        $this->lignesVente[] = $ligne;
    }

    public function getLignesVente(): array
    {
        return $this->lignesVente;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }
}