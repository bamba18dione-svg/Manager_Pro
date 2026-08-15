
<?php



class Role
{
    private int $id;
    private string $nom;
    private ?string $description;
    private string $created_at;

    public function __construct(
        int $id,
        string $nom,
        ?string $description = null,
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->created_at = $created_at;
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