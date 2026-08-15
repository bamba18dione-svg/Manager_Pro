
<?php


class ModePaiement
{
    private int $id;
    private string $nom;
    private ?string $description;

    public function __construct(
        int $id,
        string $nom,
        ?string $description = null
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
    }

    public function getNom(): string
    {
        return $this->nom;
    }
}