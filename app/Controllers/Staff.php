<?php namespace App\Controllers;

use App\Models\Config_Model;
use App\Models\Notice_Model;
use App\Models\Staff_Model;
use App\Models\Sess_Model;
use App\Models\Category_Model;
use App\Models\Forge_Model;
use App\Models\DesignConn_Model;

use App\Models\appsvc\Member_Model;
use App\Models\appsvc\Connect_Model;
use App\Models\appsvc\Bet_Model;
use App\Models\appsvc\Update_Model;

use App\Libraries\DesignConn;

class Staff extends BaseController
{

	public function __construct()
    {
        
    }

	public function index()
	{		
		if(!is_login())
		{
			$this->response->redirect('/pages/login');			
		}
		else {
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();
			
			$redirectUrl = "/pages/login";

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);
			if(!is_null($objAdmin)){
				if($objAdmin->stf_level > LEVEL_COMPANY)
					$redirectUrl = "/staff/company";
				else if($objAdmin->stf_level > LEVEL_AGENCY)
					$redirectUrl = "/staff/agency";
				else if($objAdmin->stf_level > LEVEL_EMPLOYEE)
					$redirectUrl = "/staff/employee";
				else if($objAdmin->stf_level == LEVEL_EMPLOYEE){
					$categories = $cat_model->getAll(); 
					
					$redirectUrl = "";
					foreach($categories as $objCat){
						if(strlen($objCat->cat_name) < 1)
							continue;
						if(isPermitCategory($objAdmin, $objCat)){
							$redirectUrl = "/".$objCat->cat_name."/member";
							break;
						}
					}
					if(strlen($redirectUrl) < 1)
						$redirectUrl = "/staff/password";
					
				}
			}  
			
			$this->response->redirect($redirectUrl);
			
		}
			
	}

    public function company()
	{		
		if(!is_login())
		{	
			$this->response->redirect('/pages/login');			
		}
		else {
			$config_model = new Config_Model();
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$siteName = $config_model->getSiteName();  

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_COMPANY)
				$bPermit = false;
			
			if($bPermit){
				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['menu_item_1'] = "menu-item-active";
				$arrMenubar['site_name'] = $siteName;  
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $objAdmin);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass();
				$arrSidebar['side_item_1'] = "sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level; 
				
				echo view('header', $arrMenubar );
				echo view('staff/sidebar', $arrSidebar );
				echo view('staff/company');
				echo view('footer', array("site_name"=>$siteName) );	
			} else {
				$this->response->redirect('/staff');	
			}
			
		}
	}

	public function company_edit($stf_fid)
	{
		if(!is_login())
		{	
			$this->response->redirect('/pages/login');			
		}
		else {
			$config_model = new Config_Model();
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$siteName = $config_model->getSiteName();  

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$objStaff = NULL;
			if($stf_fid > 0){
				$objStaff = $staff_model->getByFid($stf_fid);
			} else if($stf_fid < 0)
				$stf_fid = 0;

			$bPermit = true;
			if(is_null($objAdmin))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_COMPANY)
				$bPermit = false;
			else if($stf_fid > 0 && (is_null($objStaff) || $objStaff->stf_level != LEVEL_COMPANY))
				$bPermit = false;
			

			if($bPermit){
				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;  
				$arrMenubar['menu_item_1'] = "menu-item-active";
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $objAdmin);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass();
				$arrSidebar['side_item_1'] = "sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;
				//staff
				$arrData['staff'] = $objStaff;
				$arrData['stf_fid'] = $stf_fid;

				echo view('header', $arrMenubar );
				echo view('staff/sidebar', $arrSidebar );
				echo view('staff/company_edit', $arrData);
				echo view('footer', array("site_name"=>$siteName) );
			} else {
				$this->response->redirect('/staff');	
			}
		}
	}

	public function agency()
	{
		if(!is_login())
		{	
			$this->response->redirect('/pages/login');			
		}
		else {
			$config_model = new Config_Model();
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$siteName = $config_model->getSiteName();  

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_AGENCY)
				$bPermit = false;

			if($bPermit){
				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;  
				$arrMenubar['menu_item_1'] = "menu-item-active";
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $objAdmin);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass();
				$arrSidebar['side_item_2']="sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;

				echo view('header', $arrMenubar );
				echo view('staff/sidebar', $arrSidebar);
				echo view('staff/agency');
				echo view('footer', array("site_name"=>$siteName));
			} else {
				$this->response->redirect('/staff');	
			}
		}
	}

	public function agency_edit($stf_fid)
	{
		if(!is_login())
		{	
			$this->response->redirect('/pages/login');			
		}
		else {
			$config_model = new Config_Model();
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$siteName = $config_model->getSiteName();  

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$objStaff = NULL;
			if($stf_fid > 0){
				$objStaff = $staff_model->getByFid($stf_fid);
			} else if($stf_fid < 0)
				$stf_fid = 0;
			
			$bPermit = true;
			if(is_null($objAdmin))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_AGENCY)
				$bPermit = false;
			else if($stf_fid <= 0)
				$bPermit = true;
			else if(is_null($objStaff) || $objStaff->stf_level != LEVEL_AGENCY)
				$bPermit = false;
			else {
				$bPermit = $staff_model->isEnableStaff($objAdmin, $objStaff, true);
			}
				

			if($bPermit){
				
				$arrEmp = $staff_model->getSortStaffNames($objAdmin, LEVEL_COMPANY);

				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;  
				$arrMenubar['menu_item_1'] = "menu-item-active";
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $objAdmin);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass();
				$arrSidebar['side_item_2'] = "sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;

				$arrData['staff'] = $objStaff;
				$arrData['stf_fid'] = $stf_fid;
				$arrData['arrEmp'] = $arrEmp;

				echo view('header', $arrMenubar);
				echo view('staff/sidebar', $arrSidebar);
				echo view('staff/agency_edit', $arrData);
				echo view('footer', array("site_name"=>$siteName));
			} else {
				$this->response->redirect('/staff');	
			}
		}
	}


	public function employee()
	{
		if(!is_login())
		{	
			$this->response->redirect('/pages/login');			
		}
		else {
			$config_model = new Config_Model();
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$siteName = $config_model->getSiteName();  

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_EMPLOYEE)
				$bPermit = false;

			if($bPermit){
				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;  
				$arrMenubar['menu_item_1'] = "menu-item-active";
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $objAdmin);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass();
				$arrSidebar['side_item_3']="sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;
				
				echo view('header', $arrMenubar );
				echo view('staff/sidebar', $arrSidebar);
				echo view('staff/employee');
				echo view('footer', array("site_name"=>$siteName));
			} else {
				$this->response->redirect('/staff');	
			}
		}
	}

	public function employee_edit($stf_fid)
	{
		if(!is_login())
		{	
			$this->response->redirect('/pages/login');			
		}
		else {
			$config_model = new Config_Model();
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$siteName = $config_model->getSiteName();  

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$objStaff = NULL;
			if($stf_fid > 0){
				$objStaff = $staff_model->getByFid($stf_fid);
			} else if($stf_fid < 0)
				$stf_fid = 0;

			$bPermit = true;
			if(is_null($objAdmin))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_EMPLOYEE)
				$bPermit = false;
			else if($stf_fid <= 0)
				$bPermit = true;
			else if(is_null($objStaff) || $objStaff->stf_level != LEVEL_EMPLOYEE)
				$bPermit = false;
			else {
				$bPermit = $staff_model->isEnableStaff($objAdmin, $objStaff, true);
			}

			if($bPermit){

				$arrEmp = $staff_model->getSortStaffNames($objAdmin, LEVEL_AGENCY);		
				
				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;  
				$arrMenubar['menu_item_1'] = "menu-item-active";
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $objAdmin);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass();
				$arrSidebar['side_item_3'] = "sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;

				$arrData['staff'] = $objStaff;
				$arrData['stf_fid'] = $stf_fid;
				$arrData['arrEmp'] = $arrEmp;

				echo view('header', $arrMenubar);
				echo view('staff/sidebar', $arrSidebar);
				echo view('staff/employee_edit', $arrData);
				echo view('footer', array("site_name"=>$siteName));
			} else {
				$this->response->redirect('/staff');	
			}
		}
	}

	public function password()
	{
		if(!is_login())
		{	
			$this->response->redirect('/pages/login');			
		}
		else {
			$config_model = new Config_Model();
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$siteName = $config_model->getSiteName();  

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;

			if($bPermit){
				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;  
				$arrMenubar['menu_item_2'] = "menu-item-active";
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $objAdmin);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass();
				$arrSidebar['stf_level'] = $objAdmin->stf_level;
								
				echo view('header', $arrMenubar);
				echo view('staff/sidebar', $arrSidebar);
				echo view('staff/password');
				echo view('footer', array("site_name"=>$siteName));
			}
		}
	}
	
	public function admin()
	{
		if(!is_login())
		{	
			$this->response->redirect('/pages/login');			
		}
		else {
			$config_model = new Config_Model();
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$siteName = $config_model->getSiteName();  

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_ADMIN)
				$bPermit = false;

			if($bPermit){
				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;  
				$arrMenubar['menu_item_1'] = "menu-item-active";
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $objAdmin);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass();
				$arrSidebar['side_item_4']="sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;
				
				echo view('header', $arrMenubar );
				echo view('staff/sidebar', $arrSidebar);
				echo view('staff/admin');
				echo view('footer', array("site_name"=>$siteName));
			} else {
				$this->response->redirect('/staff');	
			}
		}
	}



	public function admin_edit($cat_id)
	{
		if(!is_login())
		{	
			$this->response->redirect('/pages/login');			
		}
		else {
			$config_model = new Config_Model();
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$siteName = $config_model->getSiteName();  

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$objCat = NULL;
			if($cat_id > 0){
				$objCat = $cat_model->getById($cat_id);
			} else if($cat_id < 0)
				$cat_id = 0;

			$bPermit = true;
			if(is_null($objAdmin))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_ADMIN)
				$bPermit = false;
			else if($cat_id > 0 && is_null($objCat))
				$bPermit = false;


			if($bPermit){

				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;  
				$arrMenubar['menu_item_1'] = "menu-item-active";
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $objAdmin);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass();
				$arrSidebar['side_item_4']="sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;

				$arrData['objCat'] = $objCat;

				echo view('header', $arrMenubar);
				echo view('staff/sidebar', $arrSidebar);
				echo view('staff/admin_edit', $arrData);
				echo view('footer', array("site_name"=>$siteName));
			} else {
				$this->response->redirect('/staff');	
			}
		}
	}

	public function design_connect($cat_id)
	{
		if(!is_login())
		{	
			$this->response->redirect('/pages/login');			
		} 
		else {
			$config_model = new Config_Model();
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$siteName = $config_model->getSiteName();  

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$objCat = $cat_model->getById($cat_id);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_ADMIN)
				$bPermit = false;

			if($bPermit){

				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;  
				$arrMenubar['menu_item_1'] = "menu-item-active";
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $objAdmin);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass();
				$arrSidebar['side_item_4']="sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;

				$arrData['objCat'] = $objCat;

				echo view('header', $arrMenubar);
				echo view('staff/sidebar', $arrSidebar);
				echo view('staff/design_connect', $arrData);
				echo view('footer', array("site_name"=>$siteName));
			} else {
				$this->response->redirect('/staff');	
			}
		}
	}


	//--------------------------------------------------------------------


	public function staff_login()
	{
		$uid = $this->request->getVar('uid');
		$pwd = $this->request->getVar('pwd');

		$staff_model = new Staff_Model();
		$objStaff = $staff_model->login($uid, $pwd);

		if(!is_null($objStaff)){
			if($objStaff->stf_state_active == PERMIT_OK){	//승인
				///Last Time
				$staff_model->updateLastTime($objStaff->stf_uid);
				$sessData = array('uid'=>$objStaff->stf_uid, 'logged_in'=>TRUE);

				$this->session->set($sessData);
				
//				$sess_id = $this->session->session_id;
//				$ip_addr = $this->request->getIPAddress();
//				$this->sess_model->register($sess_id, $objStaff->stf_uid, $ip_addr);

				$arrResult['code'] = RESULT_OK;
				$arrResult['status'] = "success";
			} else {									//차단
				$arrResult['code'] = RESULT_STOP;
				$arrResult['status'] = "fail";
			}
		} else {
			$arrResult['code'] = RESULT_ERROR;			//아이디,비번오류
			$arrResult['status'] = "fail";
		}
		echo json_encode($arrResult);
	}

	public function staff_logout()
	{
		$this->session->destroy();

		$arrResult['status'] = "success";
		echo json_encode($arrResult);
	}

	public function staff_list()
	{
		$level = $this->request->getVar('level');
		$search = $this->request->getVar('search');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();
			
			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin))
				$bPermit = false;
			else if($objAdmin->stf_level < $level)
				$bPermit = false;
			else if($level < LEVEL_EMPLOYEE || $level > LEVEL_COMPANY)
				$bPermit = false;
			
			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$arrStaff = $staff_model->getNamedStaff($objAdmin, $level, 0, $search);
				
				$result->status = "success";
				$result->data = $arrStaff;
				$result->cats = getPermitedCategories($cat_model->getAll(), $objAdmin);	
			} 

		}
		echo json_encode($result);	
	}


	public function staff_update()
	{
		$jsonData = $_REQUEST['json_'];
		$arrRqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$objRqStaff = $staff_model->getByFid($arrRqData['stf_fid']);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objRqStaff))
				$bPermit = false;
			else if($objAdmin->stf_level <= $objRqStaff->stf_level)
				$bPermit = false;
			else {
				$bPermit = $staff_model->isEnableStaff($objAdmin, $objRqStaff, true);
			}

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				/*
				$arrStaff = $staff_model->getStaffByEmp($objRqStaff->stf_fid, $objRqStaff->stf_level, $objRqStaff->stf_level, "", true);
				if(!is_null($arrStaff)){
					foreach($arrStaff as $objChild){
						$staff_model->updateByFid($objChild->stf_fid, $arrRqData);					
					}
				} 
				*/
				$bResult = $staff_model->updateByFid($objRqStaff->stf_fid, $arrRqData);
				if($bResult)
					$result->status = "success";
				else {
					$result->status = "fail";
					$result->code = RESULT_FAIL;
				}
			} 

		}
		echo json_encode($result);

	}


	public function staff_delete()
	{
		$jsonData = $_REQUEST['json_'];
		$arrRqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$objRqStaff = $staff_model->getByFid($arrRqData['stf_fid']);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objRqStaff))
				$bPermit = false;
			else if($objAdmin->stf_level <= $objRqStaff->stf_level)
				$bPermit = false;
			else {
				$bPermit = $staff_model->isEnableStaff($objAdmin, $objRqStaff, true);
			}

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				
				$bResult = $staff_model->deleteAllByEmp($objRqStaff);
				if($bResult)
					$result->status = "success";
				else {
					$result->status = "fail";
					$result->code = RESULT_FAIL;
				}
			} 

		}
		echo json_encode($result);

	}


	public function staff_modify()
	{
		$jsonData = $_REQUEST['json_'];
		$arrRqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$objRqStaff = $staff_model->getByFid($arrRqData['stf_fid']);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objRqStaff))
				$bPermit = false;
			else if($objAdmin->stf_level <= $objRqStaff->stf_level)
				$bPermit = false;
			else {
				$bPermit = $staff_model->isEnableStaff($objAdmin, $objRqStaff, true);
			}

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{

				$iResult = $staff_model->modifyByFid($objRqStaff->stf_fid, $arrRqData);
				if($iResult == RESULT_OK)
					$result->status = "success";
				else {
					$result->status = "fail";
					$result->code = $iResult;
				}
			} 

		}
		echo json_encode($result);

	}


	public function staff_create()
	{
		$jsonData = $_REQUEST['json_'];
		$arrRqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin))
				$bPermit = false;
			else if(!array_key_exists('stf_level', $arrRqData))
				$bPermit = false;
			else if($objAdmin->stf_level <= $arrRqData['stf_level'])
				$bPermit = false;
			

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else {

				$iResult = $staff_model->register($arrRqData);
				if($iResult == RESULT_OK)
					$result->status = "success";
				else {
					$result->status = "fail";
					$result->code = $iResult;
				}
			} 

		}
		echo json_encode($result);

	}


	public function staff_pwd()
	{
		$pwd_cur = $this->request->getVar('pwd_cur');
		$pwd_new = $this->request->getVar('pwd_new');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->login($strUid, $pwd_cur);

			$bPermit = true;
			if(is_null($objAdmin)){
				$bPermit = false;
			}

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_ERROR;
			} else{
				$bResult = $staff_model->updatePwd($objAdmin->stf_fid, $pwd_new);
				if($bResult)
					$result->status = "success";
				else {
					$result->status = "fail";
					$result->code = RESULT_FAIL;
				}	
			} 

		}
		echo json_encode($result);	
	}

	public function notice_update()
	{
		$jsonData = $_REQUEST['json_'];
		$arrRqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$notice_model = new Notice_Model();

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_ADMIN)
				$bPermit = false;
			
			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{

				$bResult = $notice_model->updateNotice($arrRqData['notice_cat'], $arrRqData['notice_content']);
				if($bResult)
					$result->status = "success";
				else {
					$result->status = "fail";
					$result->code = RESULT_FAIL;
				}
			} 

		}
		echo json_encode($result);

	}

	public function app_list()
	{
		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();
			
			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_ADMIN)
				$bPermit = false;
			
			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$arrApp = $cat_model->getAll();
				
				$result->status = "success";
				$result->data = $arrApp;
			} 

		}
		echo json_encode($result);	
	}

	public function app_clear()
	{
		$cat_id = $this->request->getVar('cat_id');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getById($cat_id);	

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_ADMIN)
				$bPermit = false;
			
			if(!$bPermit) {
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$bResult = false;

				$member_model = new Member_Model($objCat->cat_name);
				$conn_model = new Connect_Model($objCat->cat_name);
				$bet_model = new Bet_Model($objCat->cat_name);
				$update_model = new Update_Model($objCat->cat_name);
				
				if($member_model->clear() && $conn_model->clear() &&
					$bet_model->clear() && $update_model->clear()){
						$bResult = true;
				}

				if($bResult)
					$result->status = "success";
				else {
					$result->status = "fail";
					$result->code = RESULT_ERROR;
				}
			} 

		}
		echo json_encode($result);	
	}

	public function app_delete()
	{
		$cat_id = $this->request->getVar('cat_id');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();
			$forge_model = new Forge_Model();

			$objCat = $cat_model->getById($cat_id);	

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_ADMIN)
				$bPermit = false;
			
			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$bResult = false;
				if($forge_model->deleteApp($objCat->cat_name)){
					$bResult = $cat_model->deleteById($objCat->cat_id);
				}
				
				if($bResult)
					$result->status = "success";
				else {
					$result->status = "fail";
					$result->code = RESULT_ERROR;
				}
			} 

		}
		echo json_encode($result);	
	}

	public function app_create()
	{
		$jsonData = $_REQUEST['json_'];
		$arrRqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();
			$forge_model = new Forge_Model();

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_ADMIN)
				$bPermit = false;
			
			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$iResult = $cat_model->register($arrRqData);
				
				if($iResult == RESULT_OK){
					if(!$forge_model->createApp($arrRqData['cat_name'])){
						$cat_model->deleteById($arrRqData['cat_id']);						
						$iResult = RESULT_ERROR;
					}
					$staff_model->resetApp($arrRqData['cat_id']);
				}

				if($iResult == RESULT_OK)
					$result->status = "success";
				else {
					$result->status = "fail";
					$result->code = $iResult;
				}
			} 

		}
		echo json_encode($result);	
	}
	
	public function app_modify()
	{
		$jsonData = $_REQUEST['json_'];
		$arrRqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();
			$forge_model = new Forge_Model();

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$objCat = $cat_model->getById($arrRqData['cat_id']);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_ADMIN)
				$bPermit = false;
			
			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$iResult = $cat_model->modifyById($objCat->cat_id, $arrRqData);
				
				
				if($iResult == RESULT_OK){
					$result->status = "success";
					$forge_model->renameApp($objCat->cat_name, $arrRqData['cat_name']);
				}
					
				else {
					$result->status = "fail";
					$result->code = $iResult;
				}
			} 

		}
		echo json_encode($result);	
	}

	public function app_update()
	{
		$jsonData = $_REQUEST['json_'];
		$arrRqData = json_decode($jsonData, true);

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();
			
			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$objCat = $cat_model->getById($arrRqData['cat_id']);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_ADMIN)
				$bPermit = false;
			
			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$iResult = $cat_model->updateById($objCat->cat_id, $arrRqData);
				
				
				if($iResult == RESULT_OK){
					$result->status = "success";
				}
					
				else {
					$result->status = "fail";
					$result->code = $iResult;
				}
			} 

		}
		echo json_encode($result);	
	}

	public function app_change()
	{
		$cat_id1 = $this->request->getVar('cat_id1');
		$cat_id2 = $this->request->getVar('cat_id2');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();
			$notice_model = new Notice_Model();
			$designConn_model = new DesignConn_Model();

			$objCat1 = $cat_model->getById($cat_id1);	
			$objCat2 = $cat_model->getById($cat_id2);	

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat1) || is_null($objCat2))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_ADMIN)
				$bPermit = false;
			
			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				
				$cat_model->updateById($objCat1->cat_id, array('cat_order'=>$objCat2->cat_order) );
				$cat_model->updateById($objCat2->cat_id, array('cat_order'=>$objCat1->cat_order) );
				/*
				$objNotice1 = $notice_model->getNotice($objCat1->cat_id);
				$objNotice2 = $notice_model->getNotice($objCat2->cat_id);
				if(!is_null($objNotice1) && !is_null($objNotice2)){
					//Notice Change
					$notice_model->updateNotice($objCat1->cat_id, $objNotice2->notice_content);
					$notice_model->updateNotice($objCat2->cat_id, $objNotice1->notice_content);
				}			
			
				$cat_model->modifyById($objCat1->cat_id, array('cat_name'=>$objCat2->cat_name, 'cat_title'=>$objCat2->cat_title), false);
				$cat_model->modifyById($objCat2->cat_id, array('cat_name'=>$objCat1->cat_name, 'cat_title'=>$objCat1->cat_title), false);
			
				$designConn_model->changeApp($objCat1->cat_id, $objCat2->cat_id);
				$bResult = $staff_model->changeApp($objCat1->cat_id, $objCat2->cat_id);
				*/
				
				$result->status = "success";	
				
				
			} 

		}
		echo json_encode($result);	
	}



	public function design_connect_get()
	{
		$cat_id = $this->request->getVar('cat_id');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();
			
			$objCat = $cat_model->getById($cat_id);	
			
			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_ADMIN)
				$bPermit = false;
			
			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$designConn = new DesignConn();

				$result->data = $designConn->sortedFields($objCat->cat_id, true);
				$result->status = "success";

			} 

		}
		echo json_encode($result);	
	}


	public function design_connect_modify()
	{
		$cat_id = $this->request->getVar('cat_id');
		$jsonData =  $this->request->getVar('json_');
		$arrRqData = json_decode($jsonData, true);
		
		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();
			
			$objCat = $cat_model->getById($cat_id);	
			
			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level <= LEVEL_ADMIN)
				$bPermit = false;
			
			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$designConn_model = new DesignConn_Model();
				
				$bResult = $designConn_model->modifyByCatId($objCat->cat_id, $arrRqData);
				if($bResult){
					$result->status = "success";
				}
					
				else {
					$result->status = "fail";
					$result->code = RESULT_ERROR;
				}
			} 

		}
		echo json_encode($result);	
	}








}