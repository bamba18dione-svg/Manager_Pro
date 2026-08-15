
<?php


use DateTime;

class Approvisionnement
{
    private int $id;
    private string $referenceBl;
    private float $coutTotal;
    private DateTime $dateAppro;
    private ?DateTime $dateReception;
    private string $statut;
    private string $created_at;

    private Fournisseur $fournisseur;
    private ?Utilisateur $utilisateur;

    public function __construct(
        int $id,
        string $referenceBl,
        float $coutTotal = 0,
        ?DateTime $dateAppro = null,
        ?DateTime $dateReception = null,
        string $statut = 'EN_ATTENTE',
        Fournisseur $fournisseur = null,
        ?Utilisateur $utilisateur = null,
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->referenceBl = $referenceBl;
        $this->coutTotal = $coutTotal;
        $this->dateAppro = $dateAppro ?? new DateTime();
        $this->dateReception = $dateReception;
        $this->statut = $statut;
        $this->fournisseur = $fournisseur;
        $this->utilisateur = $utilisateur;
        $this->created_at = $created_at;
    }

    public function isReceived(): bool
    {
        return $this->dateReception !== null;
    }

    public function calculateTotal(): float
    {
        return $this->coutTotal;
    }

    public function updateStatut(): void
    {
        if ($this->isReceived()) {
            $this->statut = 'RECU';
        } else {
            $this->statut = 'EN_ATTENTE';
        }
    }

    public function getLignesApprovisionnement(): array
    {
        return [];
    }
}