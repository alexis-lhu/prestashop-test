<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class UploadVideo extends Module
{
    public function __construct()
    {
        $this->name = 'uploadvideo';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Alexis Lhussiez';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Upload Product Videos');
        $this->description = $this->l('Allows uploading videos for products in the back office.');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('displayAdminProductsExtra')
            && $this->registerHook('displayProductAdditionalInfo')
            && $this->createDatabaseTable();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->deleteDatabaseTable();
    }

    private function createDatabaseTable()
    {
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
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'product_videos`';
        return Db::getInstance()->execute($sql);
    }





    public function hookDisplayAdminProductsExtra($params)
    {
        $id_product = (int)$params['id_product'];
        $token = Tools::getAdminTokenLite('AdminUploadVideo');
    
        // Vérifie si le formulaire de suppression a été soumis
        if (Tools::isSubmit('id_product') && Tools::isSubmit('delete_videos')) {
            $this->deleteAllVideos($id_product);
        }
    
        // Chemin flexible pour accéder au dossier des vidéos
        $video_dir = __DIR__ . '/videos/';
        $videos = [];
    
        // Vérifie si le dossier existe et récupère les fichiers vidéo
        if (is_dir($video_dir)) {
            foreach (scandir($video_dir) as $file) {
                if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['mp4', 'avi', 'mov'])) {
                    $videos[] = $file;
                }
            }
        }
    
        // Assigner les variables au template
        $this->context->smarty->assign([
            'id_product' => $id_product,
            'upload_action' => $this->context->link->getAdminLink('AdminUploadVideo'),
            'token' => $token,
            'videos' => $videos, // Liste des vidéos récupérées
            'delete_action' => $this->context->link->getAdminLink('AdminUploadVideo'), // Action pour la suppression
        ]);
    
        // Affiche le template
        return $this->display(__FILE__, 'views/templates/admin/uploadvideo.tpl');
    }
    
    





public function hookDisplayProductAdditionalInfo($params)
{
    $id_product = (int)$params['product']['id_product'];

    // Récupère les vidéos associées au produit
    $videos = Db::getInstance()->executeS('
        SELECT video_path FROM ' . _DB_PREFIX_ . 'product_videos
        WHERE id_product = ' . $id_product
    );

    // Assigner les vidéos au template
    $this->context->smarty->assign([
        'product_videos' => $videos,
    ]);

    return $this->display(__FILE__, 'views/templates/front/productvideos.tpl');
}

    public function getAvailableVideos()
    {
        $video_dir = __DIR__ . '/videos/'; 
        $videos = [];

        if (is_dir($video_dir)) {
            foreach (scandir($video_dir) as $file) {
                if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['mp4', 'avi', 'mov'])) {
                    $videos[] = $file;
                }
            }
        } else {
            echo "Le dossier n'existe pas ou le chemin est incorrect.";
        }

        return $videos;
    }
    
    public function deleteAllVideos($id_product)
    {
        // Supprime toutes les vidéos associées au produit
        return Db::getInstance()->delete('product_videos', 'id_product = ' . (int)$id_product);
    }


}
