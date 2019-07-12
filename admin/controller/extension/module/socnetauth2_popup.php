<?php
class ControllerExtensionModulesocnetauth2popup extends Controller {

	public function index() 
	{
		$this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/module/socnetauth2&token=' . $this->session->data['token']);
	}
	
	public function install()
	{
		$this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/extension/module/install&token='.$this->session->data['token'].
		'&extension=socnetauth2');
	}
	
	public function uninstall()
	{
		$this->response->redirect(HTTPS_SERVER . 'index.php?route=extension/extension/module/uninstall&token='.$this->session->data['token'].
		'&extension=socnetauth2');
	}
}

?>