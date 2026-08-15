
<?php


use DateTime;

class Vente
{
    private int $id;
    private string $numeroFacture;
    private float $montantTotal;
    private float $montantVerse;
    private ?string $modeReglement;
    private string $statut;
    private DateTime $dateVente;
    private ?DateTime $dateEcheance;
    private string $created_at;

    private ?Utilisateur $utilisateur;
    private ?Client $client;

    public function __construct(
        int $id,
        string $numeroFacture,
        float $montantTotal,
        float $montantVerse = 0,
        ?string $modeReglement = null,
        string $statut = 'EN_ATTENTE',
        ?DateTime $dateVente = null,
        ?DateTime $dateEcheance = null,
        ?Utilisateur $utilisateur = null,
        ?Client $client = null,
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->numeroFacture = $numeroFacture;
        $this->montantTotal = $montantTotal;
        $this->montantVerse = $montantVerse;
        $this->modeReglement = $modeReglement;
        $this->statut = $statut;
        $this->dateVente = $dateVente ?? new DateTime();
        $this->dateEcheance = $dateEcheance;
        $this->utilisateur = $utilisateur;
        $this->client = $client;
        $this->created_at = $created_at;
    }

    public function getRemainingAmount(): float
    {
        return max(0, $this->montantTotal - $this->montantVerse);
    }

    public function isFullyPaid(): bool
    {
        return $this->getRemainingAmount() == 0;
    }

    public function updateStatus(): void
    {
        if ($this->isFullyPaid()) {
            $this->statut = 'PAYEE';
        } else {
            $this->statut = 'EN_ATTENTE';
        }
    }

    public function getMontantRestant(): float
    {
        return $this->getRemainingAmount();
    }

    public function getLignesVente(): array
    {
        return [];
    }
}