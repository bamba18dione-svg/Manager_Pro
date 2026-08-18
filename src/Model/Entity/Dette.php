
<?php



use DateTime;

class Dette
{
    private int $id;
    private string $ref;
    private float $montantInitial;
    private float $montantVerse;
    private float $montantRestant;
    private ?DateTime $dateEcheance;
    private string $statut;
    private string $created_at;

    private Client $client;
    private ?Vente $vente;

    public function __construct(
        int $id,
        string $ref,
        float $montantInitial,
        float $montantVerse = 0,
        ?float $montantRestant = null,
        ?DateTime $dateEcheance = null,
        string $statut = 'EN_COURS',
        Client $client = null,
        ?Vente $vente = null,
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->ref = $ref;
        $this->montantInitial = $montantInitial;
        $this->montantVerse = $montantVerse;
        $this->montantRestant =
            $montantRestant ?? ($montantInitial - $montantVerse);
        $this->dateEcheance = $dateEcheance;
        $this->statut = $statut;
        $this->client = $client;
        $this->vente = $vente;
        $this->created_at = $created_at;
    }

    public function getResteDu(): float
    {
        return max(0, $this->montantInitial - $this->montantVerse);
    }

    public function isSold(): bool
    {
        return $this->getResteDu() == 0;
    }

    public function applyPayment(float $montant): void
    {
        if ($montant <= 0) {
            throw new \InvalidArgumentException(
                "Le montant du paiement doit être positif."
            );
        }

        if ($montant > $this->getResteDu()) {
            throw new \InvalidArgumentException(
                "Le paiement dépasse le montant restant."
            );
        }

        $this->montantVerse += $montant;
        $this->montantRestant = $this->getResteDu();

        $this->updateStatut();
    }

    public function updateStatut(): void
    {
        if ($this->isSold()) {
            $this->statut = 'SOLDEE';
        } else {
            $this->statut = 'EN_COURS';
        }
    }

    public function getPaiements(): array
    {
        return [];
    }
}