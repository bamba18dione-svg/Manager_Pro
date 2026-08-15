
<?php


use DateTime;

class Paiement
{
    private int $id;
    private float $montant;
    private ?string $notes;
    private DateTime $datePaiement;
    private string $reference;

    private Dette $dette;
    private ModePaiement $modePaiement;
    private ?Utilisateur $utilisateur;

    public function __construct(
        int $id,
        float $montant,
        string $reference,
        Dette $dette,
        ModePaiement $modePaiement,
        ?string $notes = null,
        ?DateTime $datePaiement = null,
        ?Utilisateur $utilisateur = null
    ) {
        $this->id = $id;
        $this->montant = $montant;
        $this->reference = $reference;
        $this->dette = $dette;
        $this->modePaiement = $modePaiement;
        $this->notes = $notes;
        $this->datePaiement = $datePaiement ?? new DateTime();
        $this->utilisateur = $utilisateur;
    }

    public function getFormattedDate(): string
    {
        return $this->datePaiement->format('d/m/Y H:i');
    }

    public function getMontant(): float
    {
        return $this->montant;
    }
}