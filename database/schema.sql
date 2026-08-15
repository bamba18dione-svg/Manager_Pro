
DROP TABLE IF EXISTS paiements CASCADE;
DROP TABLE IF EXISTS dettes CASCADE;
DROP TABLE IF EXISTS lignes_vente CASCADE;
DROP TABLE IF EXISTS ventes CASCADE;
DROP TABLE IF EXISTS lignes_approvisionnement CASCADE;
DROP TABLE IF EXISTS approvisionnements CASCADE;
DROP TABLE IF EXISTS produits CASCADE;
DROP TABLE IF EXISTS fournisseurs CASCADE;
DROP TABLE IF EXISTS clients CASCADE;
DROP TABLE IF EXISTS utilisateurs CASCADE;
DROP TABLE IF EXISTS modes_paiement CASCADE;
DROP TABLE IF EXISTS roles CASCADE;


CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE utilisateurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    adresse TEXT,
    telephone VARCHAR(30),
    role_id INTEGER NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_utilisateur_role
        FOREIGN KEY (role_id) REFERENCES roles(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE clients (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(30),
    email VARCHAR(255),
    limite_credit NUMERIC(15,2) NOT NULL DEFAULT 0,
    solde_actuel NUMERIC(15,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT chk_client_limite_credit
        CHECK (limite_credit >= 0),

    CONSTRAINT chk_client_solde_actuel
        CHECK (solde_actuel >= 0)
);

CREATE TABLE fournisseurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    email VARCHAR(255),
    telephone VARCHAR(30),
    adresse TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE produits (
    id SERIAL PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    libelle VARCHAR(150) NOT NULL,
    categorie VARCHAR(100),
    prix_vente NUMERIC(15,2) NOT NULL,
    cout_achat NUMERIC(15,2) NOT NULL,
    stock_initial INTEGER NOT NULL DEFAULT 0,
    stock_actuel INTEGER NOT NULL DEFAULT 0,
    seuil_alerte INTEGER NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fournisseur_id INTEGER,

    CONSTRAINT chk_produit_prix_vente
        CHECK (prix_vente >= 0),

    CONSTRAINT chk_produit_cout_achat
        CHECK (cout_achat >= 0),

    CONSTRAINT chk_produit_stock_initial
        CHECK (stock_initial >= 0),

    CONSTRAINT chk_produit_stock_actuel
        CHECK (stock_actuel >= 0),

    CONSTRAINT chk_produit_seuil_alerte
        CHECK (seuil_alerte >= 0),

    CONSTRAINT fk_produit_fournisseur
        FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);

CREATE TABLE modes_paiement (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
);

CREATE TABLE ventes (
    id SERIAL PRIMARY KEY,
    numero_facture VARCHAR(50) NOT NULL UNIQUE,
    montant_total NUMERIC(15,2) NOT NULL DEFAULT 0,
    montant_verse NUMERIC(15,2) NOT NULL DEFAULT 0,
    mode_reglement VARCHAR(50),
    statut VARCHAR(30) NOT NULL DEFAULT 'EN_ATTENTE',
    date_vente TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_echeance TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    utilisateur_id INTEGER NOT NULL,
    client_id INTEGER,

    CONSTRAINT chk_vente_montant_total
        CHECK (montant_total >= 0),

    CONSTRAINT chk_vente_montant_verse
        CHECK (montant_verse >= 0),

    CONSTRAINT chk_vente_montant_verse_total
        CHECK (montant_verse <= montant_total),

    CONSTRAINT fk_vente_utilisateur
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_vente_client
        FOREIGN KEY (client_id) REFERENCES clients(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);


CREATE TABLE lignes_vente (
    id SERIAL PRIMARY KEY,
    vente_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,
    quantite INTEGER NOT NULL,
    prix_unitaire NUMERIC(15,2) NOT NULL,
    sous_total NUMERIC(15,2) NOT NULL,

    CONSTRAINT chk_ligne_vente_quantite
        CHECK (quantite > 0),

    CONSTRAINT chk_ligne_vente_prix
        CHECK (prix_unitaire >= 0),

    CONSTRAINT chk_ligne_vente_sous_total
        CHECK (sous_total >= 0),

    CONSTRAINT fk_ligne_vente_vente
        FOREIGN KEY (vente_id) REFERENCES ventes(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_ligne_vente_produit
        FOREIGN KEY (produit_id) REFERENCES produits(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE dettes (
    id SERIAL PRIMARY KEY,
    ref VARCHAR(50) NOT NULL UNIQUE,
    montant_initial NUMERIC(15,2) NOT NULL,
    montant_verse NUMERIC(15,2) NOT NULL DEFAULT 0,
    montant_restant NUMERIC(15,2) NOT NULL,
    date_echeance TIMESTAMP,
    statut VARCHAR(30) NOT NULL DEFAULT 'EN_COURS',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    client_id INTEGER NOT NULL,
    vente_id INTEGER UNIQUE,

    CONSTRAINT chk_dette_montant_initial
        CHECK (montant_initial >= 0),

    CONSTRAINT chk_dette_montant_verse
        CHECK (montant_verse >= 0),

    CONSTRAINT chk_dette_montant_restant
        CHECK (montant_restant >= 0),

    CONSTRAINT chk_dette_verse_initial
        CHECK (montant_verse <= montant_initial),

    CONSTRAINT fk_dette_client
        FOREIGN KEY (client_id) REFERENCES clients(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_dette_vente
        FOREIGN KEY (vente_id) REFERENCES ventes(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);


CREATE TABLE paiements (
    id SERIAL PRIMARY KEY,
    montant NUMERIC(15,2) NOT NULL,
    notes TEXT,
    date_paiement TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reference VARCHAR(100) NOT NULL UNIQUE,
    dette_id INTEGER NOT NULL,
    mode_paiement_id INTEGER NOT NULL,
    utilisateur_id INTEGER,

    CONSTRAINT chk_paiement_montant
        CHECK (montant > 0),

    CONSTRAINT fk_paiement_dette
        FOREIGN KEY (dette_id) REFERENCES dettes(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_paiement_mode
        FOREIGN KEY (mode_paiement_id) REFERENCES modes_paiement(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_paiement_utilisateur
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);

CREATE TABLE approvisionnements (
    id SERIAL PRIMARY KEY,
    reference_bl VARCHAR(50) NOT NULL UNIQUE,
    cout_total NUMERIC(15,2) NOT NULL DEFAULT 0,
    date_appro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_reception TIMESTAMP,
    statut VARCHAR(30) NOT NULL DEFAULT 'EN_ATTENTE',
    fournisseur_id INTEGER NOT NULL,
    utilisateur_id INTEGER,

    CONSTRAINT chk_appro_cout_total
        CHECK (cout_total >= 0),

    CONSTRAINT fk_appro_fournisseur
        FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_appro_utilisateur
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);

CREATE TABLE lignes_approvisionnement (
    id SERIAL PRIMARY KEY,
    approvisionnement_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,
    quantite_appro INTEGER NOT NULL,
    quantite_recue INTEGER NOT NULL DEFAULT 0,
    prix_achat NUMERIC(15,2) NOT NULL,
    sous_total NUMERIC(15,2) NOT NULL,

    CONSTRAINT chk_ligne_appro_quantite
        CHECK (quantite_appro > 0),

    CONSTRAINT chk_ligne_appro_quantite_recue
        CHECK (quantite_recue >= 0 AND quantite_recue <= quantite_appro),

    CONSTRAINT chk_ligne_appro_prix
        CHECK (prix_achat >= 0),

    CONSTRAINT chk_ligne_appro_sous_total
        CHECK (sous_total >= 0),

    CONSTRAINT fk_ligne_appro_appro
        FOREIGN KEY (approvisionnement_id)
        REFERENCES approvisionnements(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_ligne_appro_produit
        FOREIGN KEY (produit_id) REFERENCES produits(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE INDEX idx_ventes_client ON ventes(client_id);
CREATE INDEX idx_ventes_utilisateur ON ventes(utilisateur_id);
CREATE INDEX idx_lignes_vente_vente ON lignes_vente(vente_id);
CREATE INDEX idx_lignes_vente_produit ON lignes_vente(produit_id);
CREATE INDEX idx_dettes_client ON dettes(client_id);
CREATE INDEX idx_paiements_dette ON paiements(dette_id);
CREATE INDEX idx_appro_fournisseur ON approvisionnements(fournisseur_id);
CREATE INDEX idx_lignes_appro_produit ON lignes_approvisionnement(produit_id);

INSERT INTO roles (nom, description) VALUES
('ADMIN', 'Admin Boutique'),
('CHARGE_VENTE', 'Chargé de Vente'),
('CHARGE_STOCK', 'Chargé de Stock'),
('INVENTAIRE', 'Inventaire');

INSERT INTO modes_paiement (nom, description) VALUES
('ESPECES', 'Paiement en espèces'),
('WAVE', 'Paiement via Wave'),
('ORANGE_MONEY', 'Paiement via Orange Money'),
('CARTE', 'Paiement par carte');