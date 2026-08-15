
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
