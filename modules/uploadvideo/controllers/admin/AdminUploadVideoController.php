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
        if (Tools::isSubmit('submitAddproduct_videos')) {
            $id_product = (int)Tools::getValue('id_product');
            $uploaded_files = $_FILES['product_videos'];

            // Gère les fichiers téléchargés
            foreach ($uploaded_files['tmp_name'] as $key => $tmp_name) {
                if ($uploaded_files['error'][$key] == UPLOAD_ERR_OK) {
                    $file_name = $uploaded_files['name'][$key];
                    // Déplace le fichier vers le dossier souhaité (par ex. /img/videos/)
                    move_uploaded_file($tmp_name, _PS_IMG_DIR_ . "videos/{$id_product}_{$file_name}");
                    // Sauvegarde le chemin en base de données, si nécessaire
                }
            }
        }
    }
}
