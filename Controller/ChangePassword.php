<?php
class ChangePassword extends Controller{
	public function __construct(){
		Auth::noAuth();
		parent::__construct();		
	}
	public function index(){		
		$this->views->getView($this, 'index', [
			'page_name' => 'Cambio de contraseña',
			'function_js' => 'ChangePassword.js',
		]);
	}
}