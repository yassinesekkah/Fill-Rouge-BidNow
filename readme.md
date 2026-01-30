# BidNow

BidNow est une plateforme web de commerce électronique basée sur le principe des enchères en ligne.  
Elle permet aux vendeurs de proposer des produits via des enchères sécurisées et aux acheteurs de participer à des mises équitables, tout en garantissant la transparence, la sécurité des paiements et une gestion automatisée du cycle de vente.

---

##  Contexte du projet

Ce projet a été réalisé dans le cadre du **Projet Fil Rouge**.  
Il vise à mettre en pratique les compétences acquises en développement web (front-end et back-end) à travers la conception d’un système complet intégrant des règles métier, une gestion du temps, et une logique transactionnelle avancée.

---

##  Objectifs du projet

### Objectif général
Développer une plateforme d’enchères en ligne fiable, sécurisée et automatisée, permettant de gérer l’ensemble du cycle de vente.

### Objectifs spécifiques
- Permettre aux vendeurs de proposer des produits via des enchères
- Offrir aux acheteurs un environnement de mise équitable et compétitif
- Automatiser la validation des ventes selon des règles métier précises
- Sécuriser les paiements et les livraisons
- Développer une application web moderne, responsive et maintenable

---

##  Acteurs du système

- **Acheteur** : consulte les enchères, effectue des mises et paie les produits gagnés
- **Vendeur** : publie des produits, configure les enchères et expédie les articles vendus
- **Administrateur** : supervise la plateforme, gère les utilisateurs et contrôle les transactions

---

##  Fonctionnalités principales

###  Gestion des utilisateurs
- Inscription et authentification sécurisées
- Gestion des rôles (acheteur, vendeur, administrateur)
- Gestion du profil utilisateur

###  Gestion des produits
- Création, modification et suppression de produits
- Association des produits à des enchères
- Gestion des catégories

### ⏱ Système d’enchères
- Définition d’un prix de départ (visible)
- Définition d’un prix de réserve (confidentiel)
- Gestion de la durée des enchères
- Clôture automatique à la fin du délai
- Détermination automatique de la meilleure offre

###  Validation de la vente
- Si le prix final atteint ou dépasse le prix de réserve → vente validée automatiquement
- Si le prix final est inférieur au prix de réserve → le vendeur peut accepter ou refuser la vente

###  Paiement sécurisé (Escrow)
- Paiement effectué par l’acheteur sous 24h
- Les fonds sont versés à la plateforme
- Conservation temporaire des fonds via un système d’entiercement (escrow)

###  Expédition et transfert des fonds
- Le vendeur confirme l’expédition du produit
- Tant que l’expédition n’est pas confirmée, aucun paiement n’est versé au vendeur
- Après confirmation (et/ou réception), les fonds sont transférés au vendeur

###  Administration
- Tableau de bord administrateur
- Gestion des utilisateurs et des enchères
- Supervision des ventes et des paiements
- Accès aux prix de réserve
- Gestion des litiges

---

##  Règles métier principales

- Une enchère possède une durée limitée
- Le prix de réserve est strictement confidentiel
- La validation de la vente dépend du prix atteint
- Le paiement doit être effectué dans un délai de 24 heures
- Les fonds sont sécurisés jusqu’à confirmation de l’expédition

---

##  Contraintes techniques

- Architecture MVC
- Base de données relationnelle
- Code structuré, commenté et maintenable
- Sécurisation des accès et des transactions
- Application responsive

---

##  Évolutions possibles

- Mise automatique (auto-bid)
- Assistant intelligent pour la suggestion des prix
- Intégration d’un système de paiement en ligne réel
- Développement d’une application mobile

---