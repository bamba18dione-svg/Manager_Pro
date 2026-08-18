<?php

namespace App\Model\Entity;

class Role
{
    private string $nom;
    private ?string $description;

    public function __construct(
        string $nom,
        ?string $description = null
    ) {
        $this->nom = $nom;
        $this->description = $description;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}