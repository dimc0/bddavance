#  Mini E-Commerce Symfony  
Un projet e-commerce simple et propre développé avec **Symfony**, incluant gestion des produits, panier, commandes et espace administrateur.

---

##  Fonctionnalités

###  Côté client
- Affichage de la liste des produits  
- Affichage d’un produit (show)  
- Ajout au panier  
- Gestion du panier : quantités, suppression d’articles  
- Validation de commande  
- Connexion / déconnexion utilisateur  

###  Côté administrateur
- Création, édition et suppression de produits  
- Formulaire d'ajout / édition stylé  
- Interface d’administration minimaliste et fonctionnelle  

---
### Diagramme
<img width="611" height="616" alt="bddavancee" src="https://github.com/user-attachments/assets/1defac65-5af4-4c89-8328-5efad627dc14" />

User Story

Client
Le client peut créer un compte sur le site pour acheter des pâtisseries.  il peut parcourir la boutique Il peut consulter la fiche de chaque produit pour voir son nom, sa description, son prix, sa catégorie et le stock disponible.
Le client peut ajouter des pâtisseries à son panier et le valider (payer)

Administrateur

Il doit aussi se connecter en tant que admin. L’administrateur est responsable de la gestion du site . Il peut ajouter de nouvelles pâtisseries en précisant leur nom, leur description, leur prix, leur catégorie et leur stock. Il peut aussi modifier ou supprimer un produit si nécessaire.
L’administrateur gère les commandes passées par les clients : il peut consulter leur contenu, changer leur statut (en préparation, expédiée, livrée).



