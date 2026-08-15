
<?php


class Fournisseur
{
    private int $id;
    private string $nom;
    private ?string $email;
    private ?string $telephone;
    private ?string $adresse;
    private string $created_at;

    public function __construct(
        int $id,
        string $nom,
        ?string $email = null,
        ?string $telephone = null,
        ?string $adresse = null,
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->adresse = $adresse;
        $this->created_at = $created_at;
    }

    public function getContact(): string
    {
        return $this->telephone ?? '';
    }

    public function getSolde(): float
    {
        return 0;
    }

    public function getApprovisionnements(): array
    {
        return [];
    }
}