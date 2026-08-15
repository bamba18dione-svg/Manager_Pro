
Phase 1 — Conception UML

### Step 1.1 — Diagrammes Use Case & Classes

**Date :** Vendredi 14 Août 2026  
**Horaire :** 19h00 - 20h30  
**Statut :** Terminé


###  Objectif de ce qui a été fait

L'objectif de cette étape est de modéliser le fonctionnement de
StoreManager Pro avant de commencer le développement.

Deux types de diagrammes UML ont été réalisés :

- Diagrammes de cas d'utilisation (Use Case)
- Diagramme de classes

Cette modélisation permet d'identifier les acteurs du système,
leurs fonctionnalités ainsi que les principales entités métier
et leurs relations.

---

###  Acteurs identifiés

Le système comporte quatre profils principaux :

1. **Admin Boutique**
2. **Chargé de Vente**
3. **Chargé de Stock**
4. **Inventaire**


#### Admin Boutique

L'Admin Boutique possède l'accès global aux principales
fonctionnalités du système :

- Tableau de bord
- Ventes / POS
- Dettes clients
- Approvisionnements
- Catalogue

#### Chargé de Vente

Le Chargé de Vente possède principalement accès à :

- La caisse / POS
- La gestion des ventes
- Le suivi des dettes clients

#### Chargé de Stock

Le Chargé de Stock possède principalement accès à :

- La gestion des approvisionnements
- La réception des marchandises
- La gestion du catalogue
- Les produits et fournisseurs

#### Inventaire

L'Inventaire possède un accès limité au catalogue afin de
consulter les produits et les informations liées au stock.

---

### 📊 Diagrammes de cas d'utilisation réalisés

Un diagramme général a été réalisé afin de présenter les
interactions entre les quatre acteurs et le système.

Des diagrammes séparés ont également été réalisés pour chaque
acteur afin de mieux représenter les responsabilités de chaque
profil :


- Use Case Admin Boutique
- Use Case Chargé de Vente
- Use Case Chargé de Stock
- Use Case Inventaire

Les relations `<<include>>` sont utilisées lorsqu'une fonctionnalité
principale nécessite obligatoirement une autre fonctionnalité.

Les relations `<<extend>>` sont utilisées lorsqu'une fonctionnalité
est optionnelle ou intervient seulement dans certaines situations.

Exemple :

- La gestion d'une vente peut inclure la création de la vente,
  la gestion du panier et l'enregistrement du paiement.
- Le rappel SMS d'une dette peut être considéré comme une
  fonctionnalité optionnelle et donc utiliser `<<extend>>`.

---

###  Diagramme de classes

Le diagramme de classes permet d'identifier les principales
entités métier du système.



Les classes possèdent leurs attributs et leurs méthodes métier.

Les principales relations modélisées concernent notamment :

- Utilisateur → Vente
- Client → Vente
- Vente → LigneVente
- LigneVente → Produit
- Vente → Paiement
- Vente → Dette
- Dette → Remboursement
- Approvisionnement → Fournisseur
- Approvisionnement → LigneApprovisionnement
- LigneApprovisionnement → Produit

Les cardinalités ont été ajoutées afin de représenter les
relations entre les différentes entités.




---

### Réflexion

La conception UML permet de clarifier les responsabilités
avant de commencer l'implémentation.

La séparation des quatre acteurs permet également de préparer
le système de gestion des rôles et des permissions qui sera
implémenté plus tard dans le projet.

Le diagramme de classes servira de base pour la création des
entités POO lors de l'étape suivante.

---
#### Difficultés / Obstacles 

Durant la réalisation des diagrammes UML, plusieurs difficultés
ont été rencontrées.


La première difficulté a été de bien distinguer les responsabilités
des quatre acteurs du système :

- Admin Boutique
- Chargé de Vente
- Chargé de Stock
- Inventaire

Une difficulté importante a été de comprendre la différence entre
les relations UML `<<include>>` et `<<extend>>`.

La construction du diagramme de classes a également demandé une
réflexion sur les principales entités métier du projet.

Il a fallu identifier :

- les classes principales ;
- leurs attributs ;
- leurs méthodes ;
- les associations entre les classes ;
- les cardinalités.




### Step 1.2 — Schéma SQL PostgreSQL / SQLite

**Date :** Vendredi 14 Août 2026  
**Horaire :** 20h30 - 22h00  
**Statut :** Terminé

---

###  Objectif

L'objectif de cette étape est de transformer le modèle UML réalisé
lors du Step 1.1 en un schéma de base de données relationnelle.

Deux scripts SQL ont été créés afin de permettre à l'application
de fonctionner avec deux systèmes de gestion de base de données :

- PostgreSQL
- SQLite

Les fichiers créés sont :

    schema.sql
    schema_sqlite.sql

Le schéma respecte les principales classes et relations définies
dans le diagramme de classes.

---

###  Tables créées

À partir du diagramme de classes, les principales tables suivantes
ont été créées :

- `roles`
- `utilisateurs`
- `clients`
- `fournisseurs`
- `produits`
- `modes_paiement`
- `ventes`
- `lignes_vente`
- `dettes`
- `paiements`
- `approvisionnements`
- `lignes_approvisionnement`

Ces tables permettent de représenter les principales données
métier de StoreManager Pro.



---

###  Contraintes SQL

Des contraintes `PRIMARY KEY` ont été utilisées pour identifier
de manière unique les enregistrements.

Des contraintes `UNIQUE` ont également été ajoutées pour certaines
données qui doivent être uniques, notamment :

- email utilisateur ;
- code produit ;
- numéro de facture ;
- référence de dette ;
- référence de paiement ;
- référence de bon de livraison.

Des contraintes `CHECK` ont été ajoutées pour contrôler les valeurs
enregistrées.

Exemples :

- le prix d'un produit ne peut pas être négatif ;
- le stock ne peut pas être négatif ;
- une quantité doit être positive ;
- un montant de paiement doit être positif ;
- le montant versé d'une vente ne peut pas dépasser son montant
  total.

---

###  Version PostgreSQL

Le fichier :

    schema.sql

utilise les fonctionnalités adaptées à PostgreSQL.

Les identifiants utilisent notamment :

    SERIAL PRIMARY KEY

Les montants financiers utilisent :

    NUMERIC(15,2)

Les dates utilisent :

    TIMESTAMP

Le script contient également les index nécessaires pour améliorer
les recherches sur les principales clés étrangères.

---

###  Version SQLite

Le fichier :

    schema_sqlite.sql

a été créé pour permettre à l'application de fonctionner avec
SQLite comme solution de secours.

SQLite utilise notamment :

    INTEGER PRIMARY KEY AUTOINCREMENT

Les dates sont stockées sous forme de texte avec :

    CURRENT_TIMESTAMP

Les clés étrangères sont activées avec :

    PRAGMA foreign_keys = ON;

Le schéma SQLite reprend les mêmes tables, relations et contraintes
principales que la version PostgreSQL.



---

###  Difficultés et obstacles rencontrés

#### 1. Passage du diagramme de classes vers le modèle relationnel

La première difficulté a été de transformer les classes UML en
tables SQL.

Il fallait déterminer pour chaque classe :

- la table correspondante ;
- la clé primaire ;
- les attributs ;
- les types SQL ;
- les relations avec les autres tables.

Par exemple, la classe `LigneVente` dépend à la fois d'une `Vente`
et d'un `Produit`. Il a donc fallu créer deux clés étrangères :

    vente_id
    produit_id

---

#### 2. Gestion des cardinalités

Une difficulté importante a été de traduire les cardinalités UML
en contraintes SQL.

Par exemple :

    Vente "1" -- "*" LigneVente

signifie qu'une vente peut contenir plusieurs lignes de vente.

La table `lignes_vente` possède donc :

    vente_id INTEGER NOT NULL

avec une clé étrangère vers `ventes`.

---

#### 3. Différences entre PostgreSQL et SQLite

Une autre difficulté a été de produire deux scripts compatibles
avec deux SGBD différents.

PostgreSQL et SQLite n'utilisent pas exactement la même syntaxe
pour les identifiants auto-incrémentés.

PostgreSQL :

    SERIAL PRIMARY KEY

SQLite :

    INTEGER PRIMARY KEY AUTOINCREMENT

Il a donc fallu adapter le schéma tout en conservant la même
structure fonctionnelle.

---

#### 4. Gestion des contraintes d'intégrité

Il a fallu déterminer quelles contraintes étaient nécessaires
pour éviter les données incohérentes.

Par exemple :

    CHECK (prix_vente >= 0)

permet d'empêcher l'enregistrement d'un prix négatif.

De même :

    CHECK (quantite > 0)

permet d'empêcher l'enregistrement d'une quantité de produit
incorrecte.


###  Solutions apportées

Pour résoudre ces difficultés :

- chaque classe métier a été transformée en table ;
- les identifiants ont été définis comme clés primaires ;
- les relations UML ont été transformées en clés étrangères ;
- les cardinalités ont été traduites en contraintes SQL ;
- des contraintes `CHECK` ont été ajoutées pour contrôler les
  valeurs ;
- des contraintes `UNIQUE` ont été ajoutées pour les données
  devant être uniques ;
- deux versions du schéma ont été créées pour PostgreSQL et SQLite ;
- les données initiales des rôles et modes de paiement ont été
  ajoutées.




### Step 1.3 — Singleton Database & Fallback Automatique

 
**Horaire :** 9h00 - 10h50  
**Statut :** Terminé

---

### 🎯 Objectif

L'objectif de cette étape est de mettre en place une classe
centrale permettant de gérer la connexion à la base de données
de l'application.

Le livrable demandé est :

    src/Core/Database.php

Cette classe doit permettre :

- d'utiliser le pattern Singleton ;
- d'établir une connexion PDO à PostgreSQL ;
- de détecter une erreur de connexion PostgreSQL ;
- de basculer automatiquement vers SQLite ;


---

###  Mise en place du Singleton

Le pattern Singleton a été utilisé afin d'éviter de créer plusieurs
instances de la classe `Database`.

La classe contient une propriété statique :

    private static ?Database $instance = null;

Le constructeur de la classe est privé :

    private function __construct()

Cela empêche la création directe d'une instance avec :

    new Database();

L'accès à l'instance se fait avec la méthode :

    Database::getInstance();

La méthode vérifie si une instance existe déjà.

Si aucune instance n'existe, elle est créée.

Sinon, l'instance existante est retournée.

Cela permet à l'ensemble de l'application de réutiliser la même
instance de `Database`.

---

###  Connexion PostgreSQL

La connexion principale de l'application est PostgreSQL.

La connexion est réalisée avec PDO.

Le principe utilisé est :

    try {
        // Connexion PostgreSQL
    }

La connexion PostgreSQL est donc tentée en priorité.

Le mode :

    PDO::ERRMODE_EXCEPTION

est utilisé afin que les erreurs de connexion ou d'exécution
puissent être détectées sous forme d'exceptions.

---

### Fallback automatique vers SQLite

Si la connexion PostgreSQL échoue, l'exception `PDOException`
est récupérée avec :

    catch (PDOException $e)

Dans ce cas, l'application ne s'arrête pas immédiatement.

Elle utilise automatiquement SQLite comme solution de secours.

La base SQLite utilisée est :

    erp.db

Le fonctionnement est donc :

    Application
         |
         v
      Database
      Singleton
         |
         v
    PostgreSQL ?
       /     \
     Oui      Non
      |        |
      v        v
 PostgreSQL   SQLite
               |
               v
             erp.db

Cette stratégie permet à l'application de disposer d'une base
locale de secours lorsque PostgreSQL n'est pas disponible.

---




### Step 2.1 — Entités POO Pure
 
**Horaire :** 11h00 - 13h20  
**Statut :** Terminé

---

### 🎯 Objectif

L'objectif de cette étape est de transformer le modèle de données
défini dans le diagramme de classes UML en véritables classes PHP.

Les entités représentent les objets métier principaux de
l'application StoreManager Pro.

Elles sont placées dans :

    src/Model/Entity/

L'objectif est également d'appliquer les principes de la
programmation orientée objet, notamment :

- l'encapsulation ;
- les propriétés privées ;
- les constructeurs ;
- les méthodes métier ;
- les types des propriétés et des paramètres ;
- les relations entre les différentes entités.

---

### 📁 Entités créées

Les classes suivantes ont été créées :

    src/Model/Entity/
    │
    ├── Role.php
    ├── Utilisateur.php
    ├── Client.php
    ├── Fournisseur.php
    ├── Produit.php
    ├── Vente.php
    ├── LigneVente.php
    ├── Dette.php
    ├── Paiement.php
    ├── ModePaiement.php
    ├── Approvisionnement.php
    └── LigneApprovisionnement.php

Chaque classe correspond à une entité présente dans le modèle
UML et dans le schéma de base de données.

---

###  Encapsulation

Les propriétés des entités ont été déclarées `private`.

Par exemple, dans `Produit.php` :

    private int $id;
    private string $code;
    private string $libelle;
    private float $prixVente;
    private float $coutAchat;
    private int $stockActuel;

Les données internes de l'objet ne peuvent donc pas être
modifiées directement depuis l'extérieur de la classe.

Les méthodes publiques permettent de contrôler les opérations
réalisées sur ces données.

---

###  Méthodes métier

Les entités ne contiennent pas uniquement des attributs.

Certaines règles métier simples sont directement implémentées
dans les classes.

#### Produit

La classe `Produit` possède notamment :

    updateStock()
    getStockValue()
    isStockLow()
    getMarge()
    getTauxMarge()

Par exemple, `updateStock()` permet de modifier le stock tout en
empêchant qu'il devienne négatif.

---

#### Client

La classe `Client` possède :

    getFullName()
    getCreditRemaining()
    canAfford()
    updateSolde()
    getDettesActives()

Ces méthodes permettent notamment de vérifier la capacité d'un
client à effectuer un achat à crédit.

---

#### Dette

La classe `Dette` contient notamment :

    getResteDu()
    isSold()
    applyPayment()
    updateStatut()
    getPaiements()

La méthode `applyPayment()` permet d'appliquer un remboursement
à une dette.

Elle vérifie également que le montant du paiement ne dépasse pas
le montant restant.

---

#### Vente

La classe `Vente` possède :

    getRemainingAmount()
    isFullyPaid()
    updateStatus()
    getMontantRestant()
    getLignesVente()

Ces méthodes permettent de déterminer le montant restant à payer
et l'état de la vente.

---

#### Approvisionnement

La classe `Approvisionnement` contient :

    isReceived()
    calculateTotal()
    updateStatut()
    getLignesApprovisionnement()

Ces méthodes permettent notamment de déterminer si un
approvisionnement a été réceptionné.

---

### 🔗 Relations entre les entités

Les relations définies dans le diagramme de classes ont également
été représentées dans les objets PHP.

Par exemple :

    Utilisateur → Role

Un utilisateur possède un rôle.

De même :

    Vente → Client
    Vente → Utilisateur
    LigneVente → Produit
    Dette → Client
    Dette → Vente
    Paiement → Dette
    Paiement → ModePaiement
    Approvisionnement → Fournisseur
    LigneApprovisionnement → Produit

L'objectif est d'avoir des objets qui peuvent représenter les
relations métier plutôt que de manipuler uniquement des identifiants
isolés.

---

### 🧮 Calculs métier intégrés

Certaines opérations de calcul ont été placées directement dans
les entités.

Exemple dans `LigneVente` :

    calculateSubTotal()

Le sous-total est calculé à partir de :

    quantité × prix unitaire

Dans `Produit` :

    getMarge()

permet de calculer :

    prix de vente - coût d'achat

Et :

    getTauxMarge()

permet de calculer le taux de marge.

---

### 🛡️ Contrôle des données

Des contrôles simples ont également été intégrés dans les méthodes
métier.

Par exemple, lors d'une modification du stock, l'application
vérifie que le stock ne devient pas négatif.

Pour une dette, un paiement :

- doit être positif ;
- ne doit pas dépasser le montant restant.

Ces contrôles permettent de protéger la cohérence des objets
avant même leur enregistrement en base de données.

---

###  Difficultés et obstacles rencontrés

#### 1. Transformation du diagramme UML en classes PHP

La première difficulté a été de passer du diagramme de classes
aux fichiers PHP correspondants.

Il fallait identifier :

- les attributs de chaque classe ;
- leurs types ;
- leurs méthodes ;
- leurs relations avec les autres classes.

La solution a été de reprendre chaque classe du diagramme et de
créer son fichier PHP correspondant.

---

#### 2. Compréhension de l'encapsulation

Une autre difficulté a été de comprendre pourquoi les attributs
devaient être `private`.

L'utilisation de propriétés privées permet d'éviter qu'une autre
partie de l'application modifie directement les données internes
d'un objet.

Les modifications passent donc par des méthodes métier.

---

#### 3. Gestion des relations entre objets

Certaines entités dépendent d'autres entités.

Par exemple :

    LigneVente → Produit

et :

    Paiement → Dette
    Paiement → ModePaiement

Il a donc fallu représenter ces relations directement dans les
classes PHP.

---

#### 4. Choix des responsabilités

Il a fallu déterminer ce qui devait être réalisé dans les entités
et ce qui devait être laissé aux futurs Services et Repositories.

Les entités contiennent principalement :

- les données ;
- les comportements propres à l'objet ;
- les calculs métier simples ;
- les contrôles directement liés à l'objet.

Les requêtes SQL ne sont pas placées dans les entités.

Elles seront réalisées dans les classes Repository lors du
Step 2.2.

---

#### 5. Différence entre Entité et Repository

Une difficulté importante a été de comprendre la séparation entre
les deux.

Une entité représente un objet métier :

    Produit
    Client
    Vente
    Dette

Le Repository sera responsable de communiquer avec la base de
données.

Ainsi :

    Produit
       ↓
    représente un produit

alors que :

    ProduitRepository
       ↓
    récupère ou enregistre les produits en BDD

Cette séparation permet de respecter une architecture POO plus
propre.

---

###  Solutions apportées

Pour résoudre ces difficultés :

- une classe PHP a été créée pour chaque entité ;
- les propriétés ont été encapsulées avec `private` ;
- les types PHP ont été utilisés pour les propriétés et méthodes ;
- les relations entre les objets ont été représentées ;
- les calculs métier simples ont été placés dans les entités ;
- des validations métier ont été ajoutées dans certaines méthodes ;
- aucune requête SQL n'a été placée dans les entités ;
- l'accès à la base de données sera réservé aux Repositories.








