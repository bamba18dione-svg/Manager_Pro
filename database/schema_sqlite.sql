
PRAGMA foreign_keys = ON;

DROP TABLE IF EXISTS paiements;
DROP TABLE IF EXISTS dettes;
DROP TABLE IF EXISTS lignes_vente;
DROP TABLE IF EXISTS ventes;
DROP TABLE IF EXISTS lignes_approvisionnement;
DROP TABLE IF EXISTS approvisionnements;
DROP TABLE IF EXISTS produits;
DROP TABLE IF EXISTS fournisseurs;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS utilisateurs;
DROP TABLE IF EXISTS modes_paiement;
DROP TABLE IF EXISTS roles;

CREATE TABLE roles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE,
    description TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE utilisateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    adresse TEXT,
    telephone TEXT,
    role_id INTEGER NOT NULL UNIQUE,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (role_id) REFERENCES roles(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);



CREATE TABLE clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    prenom TEXT NOT NULL,
    telephone TEXT,
    email TEXT,
    limite_credit NUMERIC NOT NULL DEFAULT 0,
    solde_actuel NUMERIC NOT NULL DEFAULT 0,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CHECK (limite_credit >= 0),
    CHECK (solde_actuel >= 0)
);


CREATE TABLE fournisseurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    email TEXT,
    telephone TEXT,
    adresse TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE produits (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code TEXT NOT NULL UNIQUE,
    libelle TEXT NOT NULL,
    categorie TEXT,
    prix_vente NUMERIC NOT NULL,
    cout_achat NUMERIC NOT NULL,
    stock_initial INTEGER NOT NULL DEFAULT 0,
    stock_actuel INTEGER NOT NULL DEFAULT 0,
    seuil_alerte INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fournisseur_id INTEGER,

    CHECK (prix_vente >= 0),
    CHECK (cout_achat >= 0),
    CHECK (stock_initial >= 0),
    CHECK (stock_actuel >= 0),
    CHECK (seuil_alerte >= 0),

    FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);


CREATE TABLE modes_paiement (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE,
    description TEXT
);


CREATE TABLE ventes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_facture TEXT NOT NULL UNIQUE,
    montant_total NUMERIC NOT NULL DEFAULT 0,
    montant_verse NUMERIC NOT NULL DEFAULT 0,
    mode_reglement TEXT,
    statut TEXT NOT NULL DEFAULT 'EN_ATTENTE',
    date_vente TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_echeance TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    utilisateur_id INTEGER NOT NULL,
    client_id INTEGER,

    CHECK (montant_total >= 0),
    CHECK (montant_verse >= 0),
    CHECK (montant_verse <= montant_total),

    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    FOREIGN KEY (client_id) REFERENCES clients(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);


CREATE TABLE lignes_vente (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    vente_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,
    quantite INTEGER NOT NULL,
    prix_unitaire NUMERIC NOT NULL,
    sous_total NUMERIC NOT NULL,

    CHECK (quantite > 0),
    CHECK (prix_unitaire >= 0),
    CHECK (sous_total >= 0),

    FOREIGN KEY (vente_id) REFERENCES ventes(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    FOREIGN KEY (produit_id) REFERENCES produits(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);


CREATE TABLE dettes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ref TEXT NOT NULL UNIQUE,
    montant_initial NUMERIC NOT NULL,
    montant_verse NUMERIC NOT NULL DEFAULT 0,
    montant_restant NUMERIC NOT NULL,
    date_echeance TEXT,
    statut TEXT NOT NULL DEFAULT 'EN_COURS',
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    client_id INTEGER NOT NULL,
    vente_id INTEGER UNIQUE,

    CHECK (montant_initial >= 0),
    CHECK (montant_verse >= 0),
    CHECK (montant_restant >= 0),
    CHECK (montant_verse <= montant_initial),

    FOREIGN KEY (client_id) REFERENCES clients(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    FOREIGN KEY (vente_id) REFERENCES ventes(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);


CREATE TABLE paiements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    montant NUMERIC NOT NULL,
    notes TEXT,
    date_paiement TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reference TEXT NOT NULL UNIQUE,
    dette_id INTEGER NOT NULL,
    mode_paiement_id INTEGER NOT NULL,
    utilisateur_id INTEGER,

    CHECK (montant > 0),

    FOREIGN KEY (dette_id) REFERENCES dettes(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    FOREIGN KEY (mode_paiement_id) REFERENCES modes_paiement(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);


CREATE TABLE approvisionnements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    reference_bl TEXT NOT NULL UNIQUE,
    cout_total NUMERIC NOT NULL DEFAULT 0,
    date_appro TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_reception TEXT,
    statut TEXT NOT NULL DEFAULT 'EN_ATTENTE',
    fournisseur_id INTEGER NOT NULL,
    utilisateur_id INTEGER,

    CHECK (cout_total >= 0),

    FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);


CREATE TABLE lignes_approvisionnement (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    approvisionnement_id INTEGER NOT NULL,
    produit_id INTEGER NOT NULL,
    quantite_appro INTEGER NOT NULL,
    quantite_recue INTEGER NOT NULL DEFAULT 0,
    prix_achat NUMERIC NOT NULL,
    sous_total NUMERIC NOT NULL,

    CHECK (quantite_appro > 0),
    CHECK (quantite_recue >= 0 AND quantite_recue <= quantite_appro),
    CHECK (prix_achat >= 0),
    CHECK (sous_total >= 0),

    FOREIGN KEY (approvisionnement_id) REFERENCES approvisionnements(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    FOREIGN KEY (produit_id) REFERENCES produits(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

-- ============================================================
-- INDEX UTILES
-- ============================================================
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