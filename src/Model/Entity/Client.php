<?php

namespace App\Model\Entity;

class Client
{
    private string $nom;
    private string $prenom;
    private ?string $telephone;
    private ?string $email;
    private float $limiteCredit;
    private float $soldeActuel;

    public function __construct(
        string $nom,
        string $prenom,
        ?string $telephone,
        ?string $email,
        float $limiteCredit,
        float $soldeActuel
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->telephone = $telephone;
        $this->email = $email;
        $this->limiteCredit = $limiteCredit;
        $this->soldeActuel = $soldeActuel;
    }

    public function getFullName(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getLimiteCredit(): float
    {
        return $this->limiteCredit;
    }

    public function getSoldeActuel(): float
    {
        return $this->soldeActuel;
    }

    public function getCreditRemaining(): float
    {
        return $this->limiteCredit - $this->soldeActuel;
    }

    public function canAfford(float $montant): bool
    {
        return $montant <= $this->getCreditRemaining();
    }

    public function updateSolde(float $montant): void
    {
        $nouveauSolde = $this->soldeActuel + $montant;

        if ($nouveauSolde < 0) {
            throw new \Exception("Le solde ne peut pas être négatif.");
        }

        $this->soldeActuel = $nouveauSolde;
    }
}