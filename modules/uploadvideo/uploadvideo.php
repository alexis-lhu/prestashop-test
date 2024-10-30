<?php

// Empêche l'accès direct au fichier en vérifiant la version de PrestaShop
if (!defined('_PS_VERSION_')) {
    exit;
}

class UploadVideo extends Module
{
    public function __construct()
    {
        // Initialise les informations de base du module
        $this->name = 'uploadvideo';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Alexis Lhussiez';
        $this->need_instance = 0;

        parent::__construct();

        // Définit les informations de présentation du module
        $this->displayName = $this->l('Upload Product Videos');
        $this->description = $this->l('Allows uploading videos for products in the back office.');

        // Compatibilité avec les versions de PrestaShop
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        // Installe le module et enregistre les hooks nécessaires
        return parent::install()
            && $this->registerHook('displayAdminProductsExtra')
            && $this->registerHook('displayProductAdditionalInfo')
            && $this->createDatabaseTable();
    }

    public function uninstall()
    {
        // Désinstalle le module et supprime la table de la base de données
        return parent::uninstall() && $this->deleteDatabaseTable();
    }

    private function createDatabaseTable()
    {
        // Crée une table dans la base de données pour stocker les vidéos des produits
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'product_videos` (
            `id_video` INT(11) NOT NULL AUTO_INCREMENT,
            `id_product` INT(11) NOT NULL,
            `video_path` VARCHAR(255) NOT NULL,  
            PRIMARY KEY (`id_video`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';
    
        return Db::getInstance()->execute($sql);
    }
    
    private function deleteDatabaseTable()
    {
        // Supprime la table de la base de données lors de la désinstallation
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'product_videos`';
        return Db::getInstance()->execute($sql);
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        // Récupère l'ID du produit et génère un token CSRF
        $id_product = (int)$params['id_product'];
        $token = Tools::getAdminTokenLite('AdminUploadVideo');

        // Vérifie si un produit et une vidéo ont été soumis pour insertion
        if (Tools::isSubmit('id_product') && Tools::isSubmit('selected_video')) {
            if ($token === Tools::getValue("token")) {  // Validation du token CSRF
                $video_dir = '/videos/';
                $video_path = "/modules/uploadvideo" . $video_dir . Tools::getValue("selected_video");

                // Insère le chemin de la vidéo dans la base de données
                $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'product_videos`(id_product, video_path)
                        VALUES (' . Tools::getValue('id_product') . ', "' . $video_path . '");';
                        
                return Db::getInstance()->execute($sql);
            }
        }
        
        // Chemin vers le dossier des vidéos
        $video_dir = __DIR__ . '/videos/';
        $videos = [];
    
        // Vérifie si le dossier existe et récupère les fichiers vidéo disponibles
        if (is_dir($video_dir)) {
            foreach (scandir($video_dir) as $file) {
                if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['mp4', 'avi', 'mov'])) {
                    $videos[] = $file;
                }
            }
        }
    
        // Assigne les variables au template Smarty pour l'interface d'admin
        $this->context->smarty->assign([
            'id_product' => $id_product,
            'upload_action' => $this->context->link->getAdminLink('AdminUploadVideo'),
            'token' => $token,
            'videos' => $videos,
        ]);
    
        // Affiche le template pour l'ajout de vidéos en admin
        return $this->display(__FILE__, 'views/templates/admin/uploadvideo.tpl');
    }
    
    public function hookDisplayProductAdditionalInfo($params)
    {
        // Récupère l'ID du produit actuel
        $id_product = (int)$params['product']['id_product'];

        // Exécute une requête pour récupérer les vidéos associées au produit
        $videos = Db::getInstance()->executeS('
            SELECT video_path FROM ' . _DB_PREFIX_ . 'product_videos
            WHERE id_product = ' . $id_product
        );

        // Assigne les vidéos au template pour affichage sur la page produit
        $this->context->smarty->assign([
            'product_videos' => $videos,
        ]);

        // Retourne le template de la vue front pour afficher les vidéos
        return $this->display(__FILE__, 'views/templates/front/productvideos.tpl');
    }
}
