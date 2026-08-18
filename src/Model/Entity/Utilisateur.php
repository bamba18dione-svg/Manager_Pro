<?php

namespace App\Model\Entity;

class Utilisateur
{
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;
    private ?string $adresse;
    private ?string $telephone;
    private Role $role;

    public function __construct(
        string $nom,
        string $prenom,
        string $email,
        string $password,
        Role $role,
        ?string $adresse = null,
        ?string $telephone = null
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->adresse = $adresse;
        $this->telephone = $telephone;
    }

    public function getFullName(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setPassword(string $password): void
    {
        $this->password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );
    }

    public function verifierPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}