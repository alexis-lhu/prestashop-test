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
        $token = Tools::getAdminTokenLite('AdminUploadVideo');
        $this->context->smarty->assign('token', $token);
    }
}
