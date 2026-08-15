
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

