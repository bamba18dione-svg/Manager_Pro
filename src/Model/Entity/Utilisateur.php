
<?php



class Utilisateur
{
    private int $id;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;
    private ?string $adresse;
    private ?string $telephone;
    private Role $role;
    private string $created_at;

    public function __construct(
        int $id,
        string $nom,
        string $prenom,
        string $email,
        string $password,
        Role $role,
        ?string $adresse = null,
        ?string $telephone = null,
        string $created_at = ''
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->adresse = $adresse;
        $this->telephone = $telephone;
        $this->created_at = $created_at;
    }

    public function getFullName(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword(string $pwd): void
    {
        $this->password = $pwd;
    }

    public function verifierPassword(string $pwd): bool
    {
        return password_verify($pwd, $this->password);
    }

    public function getRole(): Role
    {
        return $this->role;
    }
}