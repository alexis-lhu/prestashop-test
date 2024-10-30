<?php

class AdminUploadVideoController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
    }

    public function postProcess()
    {
        // Vérifie si le formulaire de suppression a été soumis
        if (Tools::isSubmit('submitDeleteAllVideos')) {
            $id_product = (int)Tools::getValue('id_product');
            $this->deleteAllVideos($id_product);
            $this->confirmations[] = $this->l('All videos have been successfully deleted.');
        }
    }

    private function deleteAllVideos($id_product)
    {
        // Supprime les vidéos de la base de données
        Db::getInstance()->delete('product_videos', 'id_product = ' . (int)$id_product);

        // Optionnel : Supprime les fichiers vidéo du dossier
        $video_dir = _PS_IMG_DIR_ . 'videos/';
        $files = glob($video_dir . "{$id_product}_*");
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Supprime le fichier
            }
        }
    }
}
