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
        $upload_action = $this->context->link->getAdminLink('AdminUploadVideo');
    
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

        $this->context->smarty->assign([
            'product_videos' => $videos,
        ]);

        return $this->display(__FILE__, 'views/templates/front/productvideos.tpl');
    }
}
