
<?php



class Client
{
    private int $id;
    private string $nom;
    private string $prenom;
    private ?string $telephone;
    private ?string $email;
    private float $limiteCredit;
    private float $soldeActuel;
    private string $created_at;

    public function __construct(
        int $id,
        string $nom,
        string $prenom,
        float $limiteCredit = 0,
        float $soldeActuel = 0,
        ?string $telephone = null,
        ?string $email = null,
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->limiteCredit = $limiteCredit;
        $this->soldeActuel = $soldeActuel;
        $this->telephone = $telephone;
        $this->email = $email;
        $this->created_at = $created_at;
    }

    public function getFullName(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getCreditRemaining(): float
    {
        return max(0, $this->limiteCredit - $this->soldeActuel);
    }

    public function canAfford(float $montant): bool
    {
        return $montant <= $this->getCreditRemaining();
    }

    public function updateSolde(float $montant): void
    {
        $this->soldeActuel += $montant;

        if ($this->soldeActuel < 0) {
            $this->soldeActuel = 0;
        }
    }

    public function getDettesActives(): array
    {
        return [];
    }
}