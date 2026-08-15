
<?php



class Produit
{
    private int $id;
    private string $code;
    private string $libelle;
    private ?string $categorie;
    private float $prixVente;
    private float $coutAchat;
    private int $stockInitial;
    private int $stockActuel;
    private int $seuilAlerte;
    private ?Fournisseur $fournisseur;
    private string $created_at;

    public function __construct(
        int $id,
        string $code,
        string $libelle,
        float $prixVente,
        float $coutAchat,
        int $stockInitial = 0,
        int $stockActuel = 0,
        int $seuilAlerte = 0,
        ?string $categorie = null,
        ?Fournisseur $fournisseur = null,
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->libelle = $libelle;
        $this->prixVente = $prixVente;
        $this->coutAchat = $coutAchat;
        $this->stockInitial = $stockInitial;
        $this->stockActuel = $stockActuel;
        $this->seuilAlerte = $seuilAlerte;
        $this->categorie = $categorie;
        $this->fournisseur = $fournisseur;
        $this->created_at = $created_at;
    }

    public function updateStock(int $quantite): void
    {
        $nouveauStock = $this->stockActuel + $quantite;

        if ($nouveauStock < 0) {
            throw new \InvalidArgumentException(
                "Le stock ne peut pas être négatif."
            );
        }

        $this->stockActuel = $nouveauStock;
    }

    public function getStockValue(): float
    {
        return $this->stockActuel * $this->coutAchat;
    }

    public function isStockLow(): bool
    {
        return $this->stockActuel <= $this->seuilAlerte;
    }

    public function getMarge(): float
    {
        return $this->prixVente - $this->coutAchat;
    }

    public function getTauxMarge(): float
    {
        if ($this->coutAchat == 0) {
            return 0;
        }

        return ($this->getMarge() / $this->coutAchat) * 100;
    }
}