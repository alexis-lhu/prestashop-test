# Module d’Ajout de Vidéos Produits pour PrestaShop

Ce module permet aux administrateurs de PrestaShop d’ajouter des vidéos spécifiques pour chaque produit à partir de vidéos existantes stockées sur le serveur. Les vidéos sélectionnées dans le back-office sont ensuite affichées sur la page du produit.

## Fonctionnalités

1. **Ajout de vidéos par produit dans l'Admin** : Sélectionnez des vidéos depuis un dossier dédié pour les associer aux produits.
2. **Affichage côté Front-Office** : Affiche les vidéos associées sur la page produit.
3. **Aperçu et lecture de vidéos** : Les vidéos apparaissent en petit format circulaire côté front et s’agrandissent au clic pour une visualisation simplifiée.

## Installation

1. **Copier le module** : Placez le dossier `uploadvideo` dans le répertoire `/modules` de votre installation PrestaShop.
2. **Installer le module** : Depuis l’interface admin de PrestaShop, allez dans **Modules** > **Gestion des modules**, recherchez **Vidéos Produits**, puis cliquez sur **Installer**.

## Création de la Table de Données

À l’installation, le module crée une table `ps_product_videos`, qui contient :
- `id_video` : ID auto-incrémenté pour chaque vidéo.
- `id_product` : ID du produit associé à la vidéo.
- `video_path` : Chemin du fichier vidéo sélectionné.

Cette table permet une association facile et multiple de vidéos pour chaque produit.

## Utilisation

### 1. Associer une vidéo à un produit

1. **Dans l'Admin** : Lors de l’édition d’un produit, la section **Modules** apparaît.
2. **Sélectionner une vidéo** : Choisissez une vidéo dans le menu déroulant, peuplé par les vidéos présentes dans le dossier `/modules/uploadvideo/videos`.
3. **Sauvegarder** : Lorsque vous sélectionnez et sauvegardez, la vidéo est ajoutée à la base de données et s’affiche sur la page du produit.

### 2. Affichage des vidéos sur la page produit

Côté front, les vidéos associées à un produit sont affichées sous forme d’aperçu circulaire. Un clic sur la vidéo permet de l’agrandir pour une visualisation optimisée.

## Aperçu du Code

- **Classe du Module (`UploadVideo.php`)** :
  - Définit le module avec des propriétés comme le nom, la description et la compatibilité.
  - Enregistre les hooks et crée/supprime la table de données à l’installation/désinstallation.
  - Hooks utilisés :
    - `displayAdminProductsExtra` : Ajoute l’interface de sélection des vidéos dans la page de gestion produit.
    - `displayProductAdditionalInfo` : Affiche les vidéos associées sur la page produit.
  - Gère les tokens CSRF pour des envois sécurisés.
  - Contient des fonctions pour insérer et récupérer les chemins de vidéos dans la base de données.

- **Template Admin (`uploadvideo.tpl`)** : Interface pour la sélection des vidéos dans la page d’édition des produits.
- **Template Front (`productvideos.tpl`)** : Affiche les vidéos sur la page produit, avec fonctionnalité d’agrandissement au clic.

## Pré-requis

- **PrestaShop 1.7+**
- **Formats Vidéo Supportés** : `.mp4`, `.avi`, `.mov` (les vidéos doivent être placées dans le dossier `/modules/uploadvideo/videos`).

## Notes

- **Tokens CSRF** : Pour éviter les erreurs de re-soumission, rechargez la page admin si nécessaire pour mettre à jour le token.

**Auteur** : Alexis Lhussiez