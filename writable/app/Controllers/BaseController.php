<?php
namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;

use App\Models\Sess_Model;

class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = ['url', 'session', 'common_helper'];
	protected $session ;
	protected $sess_model;
	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		
		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		// $this->session = \Config\Services::session();
		
		$this->session = session();
		$this->sess_model = new Sess_Model();

	}

	protected function sess_login(){
		$sess_id = $this->session->session_id;
		$sess_uid = $this->session->uid;
		$ip_addr = $this->request->getIPAddress();
		
		$this->sess_model->register($sess_id, $sess_uid, $ip_addr);
	}
	
	protected function sess_logout(){

		$sess_id = $this->session->session_id;
		$this->sess_model->deleteById($sess_id);
		$this->session->destroy();

	}

	protected function sess_update(){

		$this->sess_model->deleteLast();

		$sess_id = $this->session->session_id;

		$objSess = $this->sess_model->getById($sess_id);

		if(is_null($objSess)){
			$this->sess_login();
			return true;
		}

		$sess_uid = $this->session->uid;
		if($objSess->sess_stf_uid !== $sess_uid){
			return false;
		}

		$this->sess_model->updateSess($objSess);

		return true;
		

	}

	


}
