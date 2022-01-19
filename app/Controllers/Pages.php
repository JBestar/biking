<?php namespace App\Controllers;

use App\Models\Config_Model;

class Pages extends BaseController
{
	public function index()
	{
		$this->response->redirect('/');
	}

    public function login()
	{		

		$config_model = new Config_Model();  
        $siteName = $config_model->getSiteName();  
		
		return view('pages/login', array("site_name"=>$siteName));		
		
	}

	public function logout(){

		//$this->sess_logout();
		$this->session->destroy();
		$this->response->redirect('/pages/login');
	}



	//--------------------------------------------------------------------

}