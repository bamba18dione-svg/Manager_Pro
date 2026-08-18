<?php

namespace App\Model\Entity;

class Fournisseur
{
    private string $nom;
    private ?string $email;
    private ?string $telephone;
    private ?string $adresse;

    public function __construct(
        string $nom,
        ?string $email = null,
        ?string $telephone = null,
        ?string $adresse = null
    ) {
        $this->nom = $nom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->adresse = $adresse;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function getContact(): string
    {
        return $this->telephone ?? $this->email ?? '';
    }
}