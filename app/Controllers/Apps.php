<?php namespace App\Controllers;

use App\Models\Config_Model;
use App\Models\Notice_Model;
use App\Models\Staff_Model;
use App\Models\Category_Model;

use App\Models\appsvc\Member_Model;
use App\Models\appsvc\Connect_Model;
use App\Models\appsvc\Bet_Model;
use App\Models\appsvc\Update_Model;
use App\Models\appsvc\Note_Model;

use App\Libraries\DesignConn;

class Apps extends BaseController
{

	private $categoryId = 0;
	private $categoryName = "";
	private $downloadPath ;
	private $settingPath ;
	

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
			$this->response->redirect('/');
		}
		
	}

	public function notice($app)
	{
		if(!is_login())
		{
			$this->response->redirect('/pages/login');			
		}
		else {
			$this->sess_update();

			$config_model = new Config_Model();
			$staff_model = new Staff_Model();
			$notice_model = new Notice_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$siteName = $config_model->getSiteName(); 

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;
			

			if($bPermit){
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$this->ClearConnect();
				//top-menu 
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $staff_model->getTopStaffByFid($objAdmin->stf_fid), $this->categoryId);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass($this->categoryName);
				$arrSidebar['side_item_1'] = "sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;
				$arrSidebar['cat_name'] = $objCat->cat_name;
				//staff
				$arrData['level'] = $objAdmin->stf_level;
				$arrData['notice'] = $notice_model->getNotice($this->categoryId);
				$arrData['cat_id'] = $objCat->cat_id;

				echo view('header', $arrMenubar);
				echo view('appsvc/sidebar', $arrSidebar);
				echo view('appsvc/notice', $arrData);
				echo view('footer', array("site_name"=>$siteName) );
			} else {
				$this->response->redirect('/');	
			}
		}
		
	}

	public function member($app)
	{
        if(!is_login())
		{
			$this->response->redirect('/pages/login');			
		}
		else {
			$this->sess_update();

			$search_uid = $this->request->getVar('uid');

			$config_model = new Config_Model();
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();
			
			$objCat = $cat_model->getByName($app);

			$siteName = $config_model->getSiteName(); 

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;
			

			if($bPermit){
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$this->ClearConnect();
				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $staff_model->getTopStaffByFid($objAdmin->stf_fid), $this->categoryId);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass($this->categoryName);
				$arrSidebar['side_item_2'] = "sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;
				$arrSidebar['cat_name'] = $objCat->cat_name;
				//staff
				$arrData['arrEmp'] = $staff_model->getSortEmpNames($objAdmin, $this->categoryId, true);
				$arrData['cat_name'] = $objCat->cat_name;
				$arrData['stf_level'] = $objAdmin->stf_level;
				$arrData['search_uid'] = $search_uid;

				echo view('header', $arrMenubar );
				echo view('appsvc/sidebar', $arrSidebar );
				if($this->categoryName == "luckyfuture" || $this->categoryName == "reantek")
					echo view('appsvc/member-luckyfuture', $arrData);
				else echo view('appsvc/member', $arrData);	
				echo view('footer', array("site_name"=>$siteName) );
				
			} else {
				$this->response->redirect('/');	
			}
		}
	}

	public function member_edit($app, $mb_fid)
	{
        if(!is_login())
		{
			$this->response->redirect('/pages/login');			
		}
		else {
			$this->sess_update();

			$config_model = new Config_Model();
			$staff_model = new Staff_Model();			
			$cat_model = new Category_Model();
			
			$objCat = $cat_model->getByName($app);

			$siteName = $config_model->getSiteName(); 

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);


			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;
			
			$objMember = NULL;
			if($bPermit){
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$member_model = new Member_Model($this->categoryName);

				if($mb_fid > 0){
					$objMember = $member_model->getByFid($mb_fid);
				} 
				
				if($mb_fid <= 0)
					$mb_fid = 0;
				else if(is_null($objMember))
					$bPermit = false;
				else $bPermit = $staff_model->isEnableMember($objAdmin, $objMember);
			}

			if($bPermit){	
				
				$this->ClearConnect();	
				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $staff_model->getTopStaffByFid($objAdmin->stf_fid), $this->categoryId);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass($objCat->cat_name);
				$arrSidebar['side_item_2'] = "sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;
				$arrSidebar['cat_name'] = $objCat->cat_name;
				//staff
				$arrData['member'] = $objMember;
				$arrData['mb_fid'] = $mb_fid;
				$arrData['arrEmp'] = $staff_model->getSortEmpNames($objAdmin, $this->categoryId, false);
				$arrData['cat_name'] = $objCat->cat_name;
				$arrData['stf_level'] = $objAdmin->stf_level;

				echo view('header', $arrMenubar );
				echo view('appsvc/sidebar', $arrSidebar );
				if($this->categoryName == "luckyfuture" || $this->categoryName == "reantek")
					echo view('appsvc/member_edit-luckyfuture', $arrData);
				else 
					echo view('appsvc/member_edit', $arrData);
				echo view('footer', array("site_name"=>$siteName) );
				
			} else {
				$this->response->redirect('/');	
			}
		}
	}

	public function member_mreg($app)
	{
        if(!is_login())
		{
			$this->response->redirect('/pages/login');			
		}
		else {
			$this->sess_update();

			$config_model = new Config_Model();
			$staff_model = new Staff_Model();			
			$cat_model = new Category_Model();
			
			$objCat = $cat_model->getByName($app);

			$siteName = $config_model->getSiteName(); 

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);


			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;
			

			if($bPermit){	
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$member_model = new Member_Model($this->categoryName);

				$this->ClearConnect();	
				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $staff_model->getTopStaffByFid($objAdmin->stf_fid), $this->categoryId);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass($objCat->cat_name);
				$arrSidebar['side_item_2'] = "sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;
				$arrSidebar['cat_name'] = $objCat->cat_name;
				//staff
				$arrData['arrEmp'] = $staff_model->getSortEmpNames($objAdmin, $this->categoryId, false);
				$arrData['cat_name'] = $objCat->cat_name;
				$arrData['stf_level'] = $objAdmin->stf_level;

				echo view('header', $arrMenubar );
				echo view('appsvc/sidebar', $arrSidebar );
				echo view('appsvc/member_mreg', $arrData);
				echo view('footer', array("site_name"=>$siteName) );
				
			} else {
				$this->response->redirect('/');	
			}
		}
	}

	
	public function member_oreg($app)
	{
        if(!is_login())
		{
			$this->response->redirect('/pages/login');			
		}
		else {
			$this->sess_update();

			$config_model = new Config_Model();
			$staff_model = new Staff_Model();			
			$cat_model = new Category_Model();
			
			$objCat = $cat_model->getByName($app);

			$siteName = $config_model->getSiteName(); 

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);


			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;
			

			if($bPermit){	
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$member_model = new Member_Model($this->categoryName);

				$this->ClearConnect();	
				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $staff_model->getTopStaffByFid($objAdmin->stf_fid), $this->categoryId);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass($objCat->cat_name);
				$arrSidebar['side_item_2'] = "sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;
				$arrSidebar['cat_name'] = $objCat->cat_name;
				//staff
				$arrData['arrEmp'] = $staff_model->getSortEmpNames($objAdmin, $this->categoryId, false);
				$arrData['cat_name'] = $objCat->cat_name;
				$arrData['stf_level'] = $objAdmin->stf_level;

				echo view('header', $arrMenubar );
				echo view('appsvc/sidebar', $arrSidebar );
				echo view('appsvc/member_oreg', $arrData);
				echo view('footer', array("site_name"=>$siteName) );
				
			} else {
				$this->response->redirect('/');	
			}
		}
	}

	public function connect($app)
	{
		if(!is_login())
		{
			$this->response->redirect('/pages/login');			
		}
		else {
			$this->sess_update();

			$config_model = new Config_Model();
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();
			

			$objCat = $cat_model->getByName($app);
			
			$siteName = $config_model->getSiteName(); 

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;
			
			if($bPermit){
				$designConn = new DesignConn();

				$this->categoryId = $objCat->cat_id;	
				$this->categoryName = $objCat->cat_name;
				$this->ClearConnect();	

				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $staff_model->getTopStaffByFid($objAdmin->stf_fid), $this->categoryId);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass($objCat->cat_name);
				$arrSidebar['side_item_3'] = "sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;
				$arrSidebar['cat_name'] = $objCat->cat_name;
				//staff
				$arrData['arrEmp'] = $staff_model->getSortEmpNames($objAdmin, $this->categoryId, true);
				$arrData['cat_name'] = $objCat->cat_name;
				$arrData['arrField'] = $designConn->sortedFdValue($this->categoryId);
				
				echo view('header', $arrMenubar);
				echo view('appsvc/sidebar', $arrSidebar);
				echo view('appsvc/connect', $arrData);
				echo view('footer', array("site_name"=>$siteName) );
				
			} else {
				$this->response->redirect('/');	
			}
		}
	}

	public function note($app)
	{
		if(!is_login())
		{
			$this->response->redirect('/pages/login');			
		}
		else {
			$this->sess_update();

			$config_model = new Config_Model();
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();
			

			$objCat = $cat_model->getByName($app);
			
			$siteName = $config_model->getSiteName(); 

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;
			else if($objCat->cat_name !== "luckystock"){
				$bPermit = false;									
			}

			if($bPermit){
				
				$this->categoryId = $objCat->cat_id;	
				$this->categoryName = $objCat->cat_name;
				
				$serverUrl = WSURL."?stf_session=".$this->session->session_id;
				
				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $staff_model->getTopStaffByFid($objAdmin->stf_fid), $this->categoryId);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass($objCat->cat_name);
				$arrSidebar['side_item_8'] = "sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;
				$arrSidebar['cat_name'] = $objCat->cat_name;
				//staff
				$arrData['level'] = $objAdmin->stf_level;
				$arrData['serverUrl'] = $serverUrl;
				$arrData['cat_name'] = $objCat->cat_name;

				echo view('header', $arrMenubar);
				echo view('appsvc/sidebar', $arrSidebar);
				echo view('appsvc/note', $arrData);
				echo view('footer', array("site_name"=>$siteName) );
				
			} else {
				
				$this->response->redirect('/');	
			}
		}
	}
	

	public function bethistory($app)
	{
		if(!is_login())
		{
			$this->response->redirect('/pages/login');			
		}
		else {
			$this->sess_update();

			$config_model = new Config_Model();
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();
			
			$objCat = $cat_model->getByName($app);

			$siteName = $config_model->getSiteName(); 

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if($bPermit){
				$this->categoryId = $objCat->cat_id;	
				$this->categoryName = $objCat->cat_name;
				$this->ClearConnect();	
				//top-menu
				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $staff_model->getTopStaffByFid($objAdmin->stf_fid), $this->categoryId);
				$arrMenubar['admin'] = $objAdmin;
				//sidebar
				$arrSidebar = getSidebarClass($objCat->cat_name);
				$arrSidebar['side_item_4'] = "sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;
				$arrSidebar['cat_name'] = $objCat->cat_name;
				//staff
				$arrData['arrEmp'] = $staff_model->getSortEmpNames($objAdmin, $this->categoryId, true);
				$arrData['cat_name'] = $objCat->cat_name;
				$arrData['stf_level'] = $objAdmin->stf_level;

				echo view('header', $arrMenubar);
				echo view('appsvc/sidebar', $arrSidebar);
				echo view('appsvc/bethistory', $arrData);	
				echo view('footer', array("site_name"=>$siteName) );
			} else {
				$this->response->redirect('/');	
			}
		}
	}



	public function updatehistory($app)
	{
		if(!is_login())
		{	
			$this->response->redirect('/pages/login');			
		} 
		else {
			$this->sess_update();

			$config_model = new Config_Model();
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();
			
			$objCat = $cat_model->getByName($app);

			$siteName = $config_model->getSiteName(); 

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_ADMIN)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if($bPermit){
				$this->categoryId = $objCat->cat_id;		
				$this->categoryName = $objCat->cat_name;
				$this->ClearConnect();

				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $staff_model->getTopStaffByFid($objAdmin->stf_fid), $this->categoryId);
				$arrMenubar['admin'] = $objAdmin;
				$arrSidebar = getSidebarClass($objCat->cat_name);
				$arrSidebar['side_item_5'] = "sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;
				$arrSidebar['cat_name'] = $objCat->cat_name;

				$arrData['cat_name'] = $objCat->cat_name;

				echo view('header', $arrMenubar );
				echo view('appsvc/sidebar', $arrSidebar );
				echo view('appsvc/updatehistory', $arrData);
				echo view('footer', array("site_name"=>$siteName) );
			} else {
				$this->response->redirect('/');	
			}
			
		}
		
	}

	public function upload($app)
	{
		if(!is_login())
		{	
			$this->response->redirect('/pages/login');			
		}
		else {
			$this->sess_update();

			$config_model = new Config_Model();
			$staff_model = new Staff_Model();			
			$cat_model = new Category_Model();
			
			$objCat = $cat_model->getByName($app);

			$siteName = $config_model->getSiteName(); 

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_ADMIN)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if($bPermit){
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$this->ClearConnect();
				$update_model = new Update_Model($this->categoryName);

				$last_version = "";
				$lastUpdate = $update_model->getLast();
				if(!is_null($lastUpdate))
					$last_version = $lastUpdate->update_version;

				try
				{
					if(isset($_FILES['upload-file'])){

						$iResult = 0;

						$file_name = $_FILES['upload-file']['name'];
						$file_size =$_FILES['upload-file']['size'];
						$file_tmp =$_FILES['upload-file']['tmp_name'];
						// $file_type=$_FILES['upload-file']['type'];
						$arrExt = explode('.', $file_name);
						$file_ext=strtolower(end($arrExt));
						// $file_error = $_FILES['upfile']['error'];

						$file_version = $_POST["upload-version"];

						$errors = array();
						$extensions= array("zip");
						
						if(strlen($file_name) < 1){
							$iResult = 2;
						} else if(in_array($file_ext, $extensions) === false){
							$iResult = 2;
							$errors[]="extension not allowed, please choose a Zip file.";
						} else if(strlen($file_version) < 1){
							$iResult = 3;
						} else if($file_size > 524288000){
							$iResult = 4;
							$errors[]='File size must be exactely 500 MB';
						} else if($file_size < 1){
							$iResult = 5;
							$errors[]='File size is zero';
						} else if(empty($file_tmp)){
							$iResult = 5;
							$errors[]='File temporary path is empty';
						}
						
						if($iResult == 0){
							$this->downloadPath = DOWNLOADROOT.$objCat->cat_name.DIRECTORY_SEPARATOR;

							if(!file_exists($this->downloadPath)){
								mkdir($this->downloadPath);
							}

							$extPath = $this->downloadPath.$file_version;
							if(!file_exists($extPath)){
								mkdir($extPath);
							} else {
								deleteDir($extPath);
								mkdir($extPath);								
							}

							$zipPath = $extPath.DIRECTORY_SEPARATOR.$file_name;
						
							move_uploaded_file($file_tmp, $zipPath);
							
							$zip = new \ZipArchive();
							
							if ($zip->open($zipPath) === TRUE) {
							
								// Extract file
								$zip->extractTo($extPath);
								$zip->close();								
								
								$dirPath = $extPath.DIRECTORY_SEPARATOR;

								$arrFileInfo = [];
								parseDir($dirPath, $arrFileInfo);

								$result = new \StdClass;
								$result->version = $file_version;
								$arrFile = [];
								$findDir = $file_version.DIRECTORY_SEPARATOR;
								foreach($arrFileInfo as $filePath){

									$pos = strpos($filePath, $findDir);
									if($pos !== false) {
										$fileSize = filesize($filePath);
										if($fileSize < 1)
											continue;

										$handle = fopen($filePath, "r");
										$contents = fread($handle, $fileSize);
										fclose($handle);
										
										$pos += strlen($findDir);
										$fileMd5 = new \StdClass;
										$fileMd5->path = substr($filePath, $pos);
										if($fileMd5->path === $file_name)
											continue;
										if(substr($fileMd5->path, -4) === ".zip")
											continue;
										$fileMd5->value = md5($contents);

										array_push($arrFile, $fileMd5);
									}				
								}
								
								$result->files = $arrFile;
								$jsonContent = json_encode($result);	
								$jsonContent = str_replace("\\\\", "/", $jsonContent);
								
								$objUpdate = $update_model->getByVersion($file_version);
								if(is_null($objUpdate))
									$update_model->insertVersion($file_version, $file_name, $jsonContent, $objAdmin->stf_uid);
								else $update_model->updateByVersion($file_version, $file_name, $jsonContent, $objAdmin->stf_uid);

								$iResult = 1;
							} 
							
						}
						$curUrl = "/".$objCat->cat_name."/upload";
						$gotoUrl = "/".$objCat->cat_name."/updatehistory";

						if($iResult == 1)
							print "<script> alert('버전업로드가 완료되었습니다.'); location.replace('".$gotoUrl."'); </script>";				
						else if($iResult == 2)
							print "<script> alert('업로드파일을 선택해주세요.'); location.replace('".$curUrl."'); </script>";				
						else if($iResult == 3)
							print "<script> alert('업로드버전을 입력해주세요.'); location.replace('".$curUrl."'); </script>";				
						else if($iResult == 4)
							print "<script> alert('파일크기 초과!!'); location.replace('".$curUrl."'); </script>";				
						else print "<script> alert('업로드중 오류가 발생되었습니다. '); location.replace('".$curUrl."');</script>";
						
					}


				}
				catch (\Exception $e)
				{
					$error = $e->getMessage();
					print "<script> alert('".$error."'); </script>";
				}

				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $staff_model->getTopStaffByFid($objAdmin->stf_fid), $this->categoryId);
				$arrMenubar['admin'] = $objAdmin;
				$arrSidebar = getSidebarClass($objCat->cat_name);
				$arrSidebar['side_item_5'] = "sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;
				$arrSidebar['cat_name'] = $objCat->cat_name;

				$arrData['last_version'] = $last_version;
				$arrData['cat_name'] = $objCat->cat_name;

				echo view('header', $arrMenubar );
				echo view('appsvc/sidebar', $arrSidebar );
				echo view('appsvc/upload', $arrData);
				echo view('footer', array("site_name"=>$siteName) );
			} else {
				$this->response->redirect('/');	
			}
		}
	}

	public function setting($app)
	{
		if(!is_login())
		{	
			$this->response->redirect('/pages/login');			
		}
		else {
			$this->sess_update();

			$config_model = new Config_Model();
			$staff_model = new Staff_Model();			
			$cat_model = new Category_Model();
			
			$objCat = $cat_model->getByName($app);

			$siteName = $config_model->getSiteName(); 

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_ADMIN)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if($bPermit){
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$this->ClearConnect();
				$update_model = new Update_Model($this->categoryName);

				$arrMenubar = getMenuClass();
				$arrMenubar['site_name'] = $siteName;
				$arrMenubar['arrCat'] = getPermitedCategories($cat_model->getAll(), $staff_model->getTopStaffByFid($objAdmin->stf_fid), $this->categoryId);
				$arrMenubar['admin'] = $objAdmin;
				$arrSidebar = getSidebarClass($objCat->cat_name);
				$arrSidebar['side_item_6'] = "sidebar-a-active";
				$arrSidebar['stf_level'] = $objAdmin->stf_level;
				$arrSidebar['cat_name'] = $objCat->cat_name;

				$arrData['cat_name'] = $objCat->cat_name;

				echo view('header', $arrMenubar );
				echo view('appsvc/sidebar', $arrSidebar );
				echo view('appsvc/setting', $arrData);
				echo view('footer', array("site_name"=>$siteName) );

			} else {
				$this->response->redirect('/');	
			}
		}
	}

	
	public function downlast($app)
	{
		if(!is_login())
		{	
			$this->response->redirect('/pages/login');			
		}
		else {

			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();
			
			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if($bPermit){
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$this->ClearConnect();
				$update_model = new Update_Model($this->categoryName);
			
				$urlPath = "";
				$bExist = false;
				$lastUpdate = $update_model->getLast();
				if(!is_null($lastUpdate)) {
					if(strlen($lastUpdate->update_version)>0 && strlen($lastUpdate->update_path)>0){
						$this->downloadPath = DOWNLOADROOT.$objCat->cat_name.DIRECTORY_SEPARATOR;
						$zipPath = $this->downloadPath.$lastUpdate->update_version.DIRECTORY_SEPARATOR.$lastUpdate->update_path;
					
						if(file_exists($zipPath)){
							$bExist = true;
							$urlPath = "/".DOWNLOADDIR."/".$objCat->cat_name."/".$lastUpdate->update_version."/".$lastUpdate->update_path;
						}
					}					
				}
				
				if($bExist){
					$this->response->redirect($urlPath);
			
				} else {
					print "<script> alert('최신버전이 없습니다.');  location.href = document.referrer; </script>";
				}
				
				
			} else {
				$this->response->redirect('/');	
			}
		}
	}

	
	//--------------------------------------------------------------------


	public function member_count($app)
	{
		$stf_fid = $this->request->getVar('stf_emp');
		$search = $this->request->getVar('search');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$objRqStaff = $staff_model->getByFid($stf_fid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;
			else if(is_null($objRqStaff))
				$bPermit = false;
			else $bPermit = $staff_model->isEnableStaff($objAdmin, $objRqStaff);

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$member_model = new Member_Model($this->categoryName);

				$arrEmpId = $staff_model->getEmpIds($objRqStaff, $this->categoryId);				
				$count = $member_model->searchCount($arrEmpId, $search);
				
				$result->status = "success";
				$result->data = $count;	
			} 

		}
		echo json_encode($result);	
	}


	public function member_list($app)
	{
		$stf_fid = $this->request->getVar('stf_emp');
		$search = $this->request->getVar('search');
		$page = $this->request->getVar('page');
		$cntPer = $this->request->getVar('cntper');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$objRqStaff = $staff_model->getByFid($stf_fid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;
			else if(is_null($objRqStaff))
				$bPermit = false;
			else $bPermit = $staff_model->isEnableStaff($objAdmin, $objRqStaff);

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$member_model = new Member_Model($this->categoryName);

				$arrEmp = $staff_model->getNamedStaff($objRqStaff, LEVEL_EMPLOYEE, $this->categoryId);
				$arrEmpId = $staff_model->getEmpIds($objRqStaff, $this->categoryId);

				$arrMember = $member_model->searchList($arrEmpId, $page, $cntPer, $search);
				if(!is_null($arrMember) && !is_null($arrEmp)){
					foreach($arrMember as $objMember){
						foreach($arrEmp as $objEmp){
							if($objMember->mb_emp_fid == $objEmp->stf_fid){
								$objMember->mb_emp_name = $objEmp->stf_name;
								$objMember->mb_emp_color = $objEmp->stf_color;
								break;
							}
						}
					}
				} 

				$result->status = "success";
				$result->data = $arrMember;	
			} 

		}
		echo json_encode($result);	
	}


	public function member_update($app)
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

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;								
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$member_model = new Member_Model($this->categoryName);

				$arrEmpId = $staff_model->getEmpIds($objAdmin, $this->categoryId);
				
				$bResult = $member_model->updateByFid($arrEmpId, $arrRqData['mb_fid'], $arrRqData);
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

	public function member_delete($app)
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

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;	
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$member_model = new Member_Model($this->categoryName);

				$arrEmpId = $staff_model->getEmpIds($objAdmin, $this->categoryId);

				$bResult = $member_model->deleteByFid($arrEmpId, $arrRqData['mb_fid']);
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


	public function member_modify($app)
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

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;
			
			if($bPermit) 
			{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$member_model = new Member_Model($this->categoryName);
				
				$objMember = $member_model->getByFid($arrRqData['mb_fid']);
				
				if(is_null($objMember))
					$bPermit = false;
				else $bPermit = $staff_model->isEnableMember($objAdmin, $objMember);
			}

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{

				$iResult = $member_model->modifyByFid($objMember->mb_fid, $arrRqData);
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


	public function member_create($app)
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

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$member_model = new Member_Model($this->categoryName);
				
				$iResult = $member_model->register($arrRqData);
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

	/*
	public function member_autocreate($app)
	{

		$start = intval($this->request->getVar('start'));
		$end = intval($this->request->getVar('end'));
		$mode = intval($this->request->getVar('mode'));
		$inc = intval($this->request->getVar('inc'));
		$arrRqData['mb_vip'] = intval($this->request->getVar('mb_vip'));
		$mb_uid_pre = strval($this->request->getVar('mb_uid_pre'));
		$mb_uid_suf = strval($this->request->getVar('mb_uid_suf'));
		$mb_emp_name = strval($this->request->getVar('mb_emp_name'));
		$mb_pwd = strval($this->request->getVar('mb_pwd'));
		$mb_name = strval($this->request->getVar('mb_name'));
		$arrRqData['mb_phone'] = strval($this->request->getVar('mb_phone'));
		$arrRqData['mb_time_limit'] = strval($this->request->getVar('mb_time_limit'));

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_ADMIN)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$member_model = new Member_Model($this->categoryName);
				
				$objEmp = $staff_model->getByName($mb_emp_name);
				if(!is_null($objEmp))
					$arrRqData['mb_emp_fid'] = $objEmp->stf_fid;

				for($idx=$start; $idx<=$end; $idx++){
					$num = $inc > 0 ? $idx * $inc : $idx;
					
					if($end < 10 || $idx >= 10 || $mode == 1){
						$arrRqData['mb_uid'] = $mb_uid_pre.$num.$mb_uid_suf;
					} else {
						$arrRqData['mb_uid'] = $mb_uid_pre."0".$num.$mb_uid_suf;
					}
					if(strlen($mb_pwd) < 1)
						$arrRqData['mb_pwd'] = $arrRqData['mb_uid'];
					else $arrRqData['mb_pwd'] = $mb_pwd;

					if(strlen($mb_name) < 1)
						$arrRqData['mb_name'] = $arrRqData['mb_uid'];
					else $arrRqData['mb_name'] = $mb_name.$idx;

					$iResult = $member_model->register($arrRqData);
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
	*/

	public function member_creates($app)
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

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$member_model = new Member_Model($this->categoryName);

				$iResult = 0;
				if(!is_null($arrRqData['mb_accounts']) && count($arrRqData['mb_accounts']) > 0){
					foreach($arrRqData['mb_accounts'] as $account){
						$arrRqData['mb_uid'] = $account['id'];
						$arrRqData['mb_pwd'] = $account['pwd'];
						if(array_key_exists('name', $account)){
							$arrRqData['mb_name'] = $account['name'];
						}
						$iResult = $member_model->register($arrRqData);
						if($iResult != RESULT_OK)
							break;
					}	
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

	public function member_createids($app)
	{
		$create_count = $this->request->getVar('create_count');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$member_model = new Member_Model($this->categoryName);

				$keygen = new \App\Libraries\KeyGen();

				$seed = microtime(true);
				$arrData = [];
				$arrIds = [];
				$nCount = 0;
				while ($nCount < $create_count)  
				{  		
								
					//$strId = generateId($seed++);
					$strId = $keygen->generateString(20, $seed+=1111);
					$strPwd = $keygen->generateString(8, $seed+=1111);

					if(!in_array($strId, $arrIds) && is_null($member_model->getByUid($strId))){
						$objData = new \StdClass;
						$objData->id= $strId;
						$objData->pwd = $strPwd;
						array_push($arrData, $objData);
						array_push($arrIds, $strId);
					 	$nCount ++;
					}
				}  

				$result->status = "success";
				$result->data = $arrData;
			} 

		}
		echo json_encode($result);

	}

	public function connector_count($app)
	{
		$stf_fid = $this->request->getVar('stf_emp');
		$search = $this->request->getVar('search');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$objRqStaff = $staff_model->getByFid($stf_fid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;
			else if(is_null($objRqStaff))
				$bPermit = false;
			else $bPermit = $staff_model->isEnableStaff($objAdmin, $objRqStaff);

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$this->ClearConnect();
				$connect_model = new Connect_Model($this->categoryName);

				$arrEmpId = $staff_model->getEmpIds($objRqStaff, $this->categoryId);				
				$count = $connect_model->searchCount($arrEmpId, $search);
				
				$result->status = "success";
				$result->data = $count;	
			} 

		}
		echo json_encode($result);	
	}


	public function connector_list($app)
	{
		$stf_fid = $this->request->getVar('stf_emp');
		$search = $this->request->getVar('search');
		$page = $this->request->getVar('page');
		$cntPer = $this->request->getVar('cntper');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {	
			$this->sess_update();	

			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$objRqStaff = $staff_model->getByFid($stf_fid);

			$bPermit = true;
			if(is_null($objAdmin)  || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;
			else if(is_null($objRqStaff))
				$bPermit = false;
			else $bPermit = $staff_model->isEnableStaff($objAdmin, $objRqStaff);

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$designConn = new DesignConn();

				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$this->ClearConnect();
				$connect_model = new Connect_Model($this->categoryName);

				$arrEmpId = $staff_model->getEmpIds($objRqStaff, $this->categoryId);
				$arrEmp = $staff_model->getCategoryStaff($objRqStaff, LEVEL_EMPLOYEE, $this->categoryId);
				$arrConnect = $connect_model->searchList($arrEmpId, $page, $cntPer, $search);

				$iAct = STATE_DISABLE;
				if(array_key_exists('app.connect', $_ENV) && $_ENV['app.connect'] == STATE_ACTIVE)
					$iAct = STATE_ACTIVE;

				if(!is_null($arrConnect) && !is_null($arrEmp)){
					foreach($arrConnect as $objConnect){
						foreach($arrEmp as $objEmp){
							if($objConnect->mb_emp_fid == $objEmp->stf_fid){
								$objConnect->mb_emp_color = $objEmp->stf_color;
								break;
							}
						}
					}
	
				}
				
				$result->status = "success";
				$result->data = $arrConnect;	
				$result->act = $iAct;	
				$result->field = $designConn->sortedFdKey($this->categoryId);	
			} 

		}
		echo json_encode($result);	
	}



	public function bet_count($app)
	{
		
		$jsonData = $_REQUEST['json_'];
		$arrRqData = json_decode($jsonData, true);

		$stf_fid = $arrRqData['stf_emp'];
		
		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$objRqStaff = $staff_model->getByFid($stf_fid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;
			else if(is_null($objRqStaff))
				$bPermit = false;
			else $bPermit = $staff_model->isEnableStaff($objAdmin, $objRqStaff);

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$bet_model = new Bet_Model($this->categoryName);

				$arrEmpId = $staff_model->getEmpIds($objRqStaff, $this->categoryId);				
				$count = $bet_model->searchCount($arrEmpId, $arrRqData, $this->categoryName);
				
				$result->status = "success";
				$result->data = $count;	
			} 

		}
		echo json_encode($result);	
	}


	public function bet_list($app)
	{
		$jsonData = $_REQUEST['json_'];
		$arrRqData = json_decode($jsonData, true);

		$stf_fid = $arrRqData['stf_emp'];
		$page = $arrRqData['page'];
		$cntPer = $arrRqData['cntper'];

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$objRqStaff = $staff_model->getByFid($stf_fid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;
			else if(is_null($objRqStaff))
				$bPermit = false;
			else $bPermit = $staff_model->isEnableStaff($objAdmin, $objRqStaff);

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$bet_model = new Bet_Model($this->categoryName);

				$arrEmpId = $staff_model->getEmpIds($objRqStaff, $this->categoryId);
				$arrEmp = $staff_model->getCategoryStaff($objRqStaff, LEVEL_EMPLOYEE, $this->categoryId);
				$arrBet = $bet_model->searchList($arrEmpId, $page, $cntPer, $arrRqData, $this->categoryName);
				if(!is_null($arrBet) && !is_null($arrEmp)){
					foreach($arrBet as $objBet){
						foreach($arrEmp as $objEmp){
							if($objBet->mb_emp_fid == $objEmp->stf_fid){
								$objBet->mb_emp_color = $objEmp->stf_color;
								break;
							}
						}
					}
				}

				$result->status = "success";
				$result->data = $arrBet;	
			} 

		}
		echo json_encode($result);	
	}


	
	public function bet_delete($app)
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

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_ADMIN)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;
			
			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$bet_model = new Bet_Model($this->categoryName);

				$bResult = $bet_model->deleteByUid($arrRqData['mb_uid'], $arrRqData);
				
				$result->status = "success";
			} 

		}
		echo json_encode($result);	
	}



	public function update_count($app)
	{
		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_ADMIN)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$update_model = new Update_Model($this->categoryName);

				$count = $update_model->searchCount();
				
				$result->status = "success";
				$result->data = $count;	
			} 

		}
		echo json_encode($result);	
	}


	public function update_list($app)
	{
		
		$page = $this->request->getVar('page');
		$cntPer = $this->request->getVar('cntper');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_ADMIN)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$update_model = new Update_Model($this->categoryName);

				$arrUpdate = $update_model->searchList($page, $cntPer);

				$result->status = "success";
				$result->data = $arrUpdate;	
			} 

		}
		echo json_encode($result);	
	}


	public function update_delete($app)
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

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_ADMIN)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$update_model = new Update_Model($this->categoryName);

				$objUpdate = $update_model->getById($arrRqData['update_id']);

				$this->downloadPath = DOWNLOADROOT.$objCat->cat_name.DIRECTORY_SEPARATOR;
				if(!is_null($objUpdate)){
					if(file_exists($this->downloadPath.$objUpdate->update_version)){
						deleteDir($this->downloadPath.$objUpdate->update_version);
					}					
				}			

				$bResult = $update_model->deleteById($arrRqData['update_id']);

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



	public function account_count($app)
	{
		$search = $this->request->getVar('search');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_ADMIN)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->settingPath = DOWNLOADROOT.$objCat->cat_name.DIRECTORY_SEPARATOR."setting";
				
				$arrDir = [];
				
				getSubDir($this->settingPath, $search, $arrDir);
				
				$result->status = "success";
				$result->data = count($arrDir);	
			} 

		}
		echo json_encode($result);	
	}


	public function account_list($app)
	{
		$search = $this->request->getVar('search');
		$page = $this->request->getVar('page');
		$cntPer = $this->request->getVar('cntper');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_ADMIN)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->settingPath = DOWNLOADROOT.$objCat->cat_name.DIRECTORY_SEPARATOR."setting";

				$arrDir = [];				
				getSubDir($this->settingPath, $search, $arrDir);
				
				sort($arrDir);

				$count = count($arrDir);
				$start = $cntPer*($page-1);
				$end = $start + $cntPer;

				if($end > $count)
					$end = $count;

				$arrData = [];
				if($start >= 0){
					for($idx = $start; $idx<$end; $idx++){
						array_push($arrData, $arrDir[$idx]);	
					}
				}

				$result->status = "success";
				$result->data = $arrData;	
			} 

		}
		echo json_encode($result);	
	}


	public function setting_count($app)
	{
		$account = $this->request->getVar('account');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin)  || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_ADMIN)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$arrFile = [];

				if(strlen($account) > 0){
					$this->settingPath = DOWNLOADROOT.$objCat->cat_name.DIRECTORY_SEPARATOR."setting";

					$dirPath = $this->settingPath.DIRECTORY_SEPARATOR.$account.DIRECTORY_SEPARATOR."*.*";
					$arrFile = glob($dirPath);
				}
				
				$count = count($arrFile);
				
				$result->status = "success";
				$result->data = count($arrFile);	
			} 

		}
		echo json_encode($result);	
	}


	public function setting_list($app)
	{
		$account = $this->request->getVar('account');
		$page = $this->request->getVar('page');
		$cntPer = $this->request->getVar('cntper');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_ADMIN)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				
				$arrFile = [];

				if(strlen($account) > 0){
					$this->settingPath = DOWNLOADROOT.$objCat->cat_name.DIRECTORY_SEPARATOR."setting";
					$dirPath = $this->settingPath.DIRECTORY_SEPARATOR.$account.DIRECTORY_SEPARATOR."*.*";
					
					$arrFile = glob($dirPath);

					// sort files by last modified date
					usort($arrFile, function($x, $y) {
						return filemtime($x) < filemtime($y);
					});
				}
				
				$count = count($arrFile);
				$start = $cntPer*($page-1);
				$end = $start + $cntPer;

				if($end > $count)
					$end = $count;
					
				$arrData = [];
				if($start >= 0){
					for($idx = $start; $idx<$end; $idx++){
						$objFile = new \StdClass;
						$objFile->account = $account;
						$objFile->name = basename($arrFile[$idx]);
						$objFile->modified = date ("Y-m-d H:i", filemtime($arrFile[$idx]));
						array_push($arrData, $objFile);	
					}
				}

				$result->status = "success";
				$result->data = $arrData;	
			} 

		}
		echo json_encode($result);	
	}


	public function setting_delete($app)
	{
		$account = $this->request->getVar('account');
		$file = $this->request->getVar('file');

		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_ADMIN)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				
				$bResult = false;
				if(strlen($account) > 0 && strlen($file) > 0 ){
					$this->settingPath = DOWNLOADROOT.$objCat->cat_name.DIRECTORY_SEPARATOR."setting";
					$filePath = $this->settingPath.DIRECTORY_SEPARATOR.$account.DIRECTORY_SEPARATOR.$file;
					$bResult = unlink($filePath);
				}	
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


		
	public function setting_download($app)
	{
		$account = $this->request->getVar('account');
		$fileName = $this->request->getVar('file');

		if(!is_login())
		{	
			$this->response->redirect('/pages/login');			
		}
		else {

			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_ADMIN)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;

			if($bPermit){
				$bExist = false;
				
				$filePath = "";
				if(strlen($account)>0 && strlen($fileName)>0){
					$this->settingPath = DOWNLOADROOT.$objCat->cat_name.DIRECTORY_SEPARATOR."setting";
					$filePath = $this->settingPath.DIRECTORY_SEPARATOR.$account.DIRECTORY_SEPARATOR.$fileName;
				
					if(file_exists($filePath)){
						$bExist = true;
					}
				}	
				
				if($bExist){
					$file = new \CodeIgniter\Files\File($filePath);

					// $mimeType = $file->getMimeType();
					$size = $file->getSize();
		
					header("Pragma: no-cache"); 
					header("Expires: 0"); 
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Cache-Control: private', false);
					header("Content-type: application/octet-stream"); 
					header("Content-Disposition: attachment; filename=$fileName"); 
					header('Content-Transfer-Encoding: binary');
					header('Content-Length: '.$size);
					header('Connection: close');
					readfile("$filePath");		
				} 				
				
			} else {
				$this->response->redirect('/');	
			}
		}
	}

	public function note_list($app){
		
		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {	
			$this->sess_update();
				
			$staff_model = new Staff_Model();
			$cat_model = new Category_Model();

			$objCat = $cat_model->getByName($app);

			$strUid = $this->session->uid;
			$objAdmin = $staff_model->getByUid($strUid);

			$bPermit = true;
			if(is_null($objAdmin) || is_null($objCat))
				$bPermit = false;
			else if($objAdmin->stf_level < LEVEL_EMPLOYEE)
				$bPermit = false;
			else if(!isPermitCategory($staff_model->getTopStaffByFid($objAdmin->stf_fid), $objCat))
				$bPermit = false;
			else if($objCat->cat_name != "luckystock")
				$bPermit = false;

			if(!$bPermit){
				$result->status = "fail";
				$result->code = RESULT_STOP;
			} else{
				$this->categoryId = $objCat->cat_id;
				$this->categoryName = $objCat->cat_name;
				$note_model = new Note_Model($this->categoryName);

				$arrEmpId = $staff_model->getParentIds($objAdmin);
				
				$arrData = [];
				$arrNote = $note_model->gets();
				if(!is_null($arrNote)){
					foreach($arrNote as $objNote){
						if($objNote->note_uid == $objAdmin->stf_uid){
							$objNote->type = 1;
						} else if(in_array($objNote->note_uid, $arrEmpId) || $objNote->stf_level > LEVEL_COMPANY){
							$objNote->type = 0;
						} else continue;
						
						array_push($arrData, $objNote);
					}
				}

				$result->status = "success";
				$result->data = $arrData;	

				$user = new \StdClass;
				$user->uid = $objAdmin->stf_uid;
				$user->name = $objAdmin->stf_nickname;
				$user->level = $objAdmin->stf_level;

				$result->user = $user;
			} 

		}
		echo json_encode($result);	


	}

	public function keepalive($app){
		
		$result = new \StdClass;
		if(!is_login())
		{
			$result->status = "logout";
		}
		else {		

			$this->sess_update();
			
			$result->status = "success";
			
		}
		echo json_encode($result);	

	}



	//-------------------------------------


	public function login($app)
	{
		// result=0: 아이디틀림
		// result=1: 정상
		// result=2: 비번틀림
		// result=3: 기간만기
		// result=4: 계정차단
		// result=5: 중복로그인
		// result=6: DB오류
		// result=7: url오류
		$logHead = "<AppLogin>";
		$uid = $this->request->getVar('username');
		$pwd = $this->request->getVar('password');
		$force = intval($this->request->getVar('force'));
		$ip_address = $this->request->getIPAddress();
		
		$arrResult = [];
		
		$cat_model = new Category_Model();
		
		$objCat = $cat_model->getByName($app);
		if(is_null($objCat)) {
			$arrResult['result'] = APPRESULT_NO_APP;
		} else {
			$this->categoryId = $objCat->cat_id;
			$this->categoryName = $objCat->cat_name;
			$this->ClearConnect();

			$member_model = new Member_Model($this->categoryName);
			$staff_model = new Staff_Model();
			$conn_model = new Connect_Model($this->categoryName);

			$objMember = $member_model->getByUid($uid);
			
			if(is_null($objMember)){
				$arrResult['result'] = APPRESULT_NONE_ID;
			} else {
				$objMember = $member_model->login($uid, $pwd);
				if(is_null($objMember)){
					$arrResult['result'] = APPRESULT_ERROR_PWD;
				} else {
					
					$sess_id = $this->session->session_id;
					$connRepeat = $conn_model->getRepeatUid($objMember->mb_uid);

					$tmNow = time();
					$tmLimit = strtotime($objMember->mb_time_limit);
					if($tmLimit < $tmNow) {
						$arrResult['result'] = APPRESULT_EXPIRED;
					} else if($objMember->mb_state_active != PERMIT_OK || 
							!$staff_model->isPermitMember($objMember, $this->categoryId) ||
							$objCat->cat_stop == 1) {
						$arrResult['result'] = APPRESULT_BLOCK;
					} else if($force != 1 && !is_null($connRepeat)){
						$arrResult['result'] = APPRESULT_DUPLICATE;
					} else if($force == 1 && !is_null($connRepeat) && $connRepeat->sess_pub_addr !== $ip_address){
						$arrResult['result'] = APPRESULT_DUPLICATE;
					} else if($this->categoryName == "luckysheet" && $objMember->mb_prop_1 == 1 ){
						$arrResult['result'] = APPRESULT_BLOCK;
					} else {
						if($force == 1 && !is_null($connRepeat))
							$conn_model->deleteByUid($objMember->mb_uid);

						if($conn_model->register($sess_id, $objMember, $ip_address)) {
							writeLog($logHead.$this->categoryName.">>userId=".$objMember->mb_uid.">>sessionId=".$sess_id.">>ip=".$ip_address);
							//Last Time
							$this->categoryName = $objCat->cat_name;
							$member_model->updateLastTime($objMember->mb_uid);
							$sessData = array('uid'=>$objMember->mb_uid, $this->categoryName=>TRUE);
							$this->session->set($sessData);

							$arrResult['result'] = APPRESULT_OK;
							$arrResult['remained'] = strval($tmLimit-$tmNow);
							$arrResult['vip'] = strval($objMember->mb_vip);
							if($this->categoryName == "luckyfuture" || $this->categoryName == "reantek")		//럭키퓨처
								$arrResult['order'] = $objMember->mb_prop_1;
						} else $arrResult['result'] = APPRESULT_FAIL_SAVE;
						
					}
				}	
			}
		}
		echo json_encode($arrResult);
	}

	
	public function logout($app){

		$cat_model = new Category_Model();
		$arrResult = [];
		$objCat = $cat_model->getByName($app);
		if(is_null($objCat)) {
			$arrResult['result'] = APPRESULT_NO_APP;
		} else {
			$this->categoryId = $objCat->cat_id; 
			$this->categoryName = $objCat->cat_name;
			$conn_model = new Connect_Model($this->categoryName);
			$sess_id = $this->session->session_id;
			$conn_model->deleteById($sess_id);
			$arrResult['result'] = APPRESULT_OK;
		}	
		$this->session->destroy();

		echo json_encode($arrResult);
	}
	
	
	protected function app_logout($app){

		$cat_model = new Category_Model();
		
		$objCat = $cat_model->getByName($app);
		if(!is_null($objCat)) {
			
			$conn_model = new Connect_Model($objCat->cat_name);
			$sess_id = $this->session->session_id;
			$conn_model->deleteById($sess_id);
		}	
		$this->session->destroy();

	}
	
	public function activekeep($app)
	{
		// result=1: 정상
		// result=3: 기간만기
		// result=4: 계정차단
		// result=10: 세션아웃
		$logHead = "<ActiveKeep>";
		$arrResult = [];
		
		$cat_model = new Category_Model();
		
		$objCat = $cat_model->getByName($app);
		if(is_null($objCat)) {
			$arrResult['result'] = APPRESULT_NO_APP;
		} else {
			$this->categoryId = $objCat->cat_id;
			$this->categoryName = $objCat->cat_name;
			$this->ClearConnect();

			if(!is_appLogin($this->categoryName))
			{
				$arrResult['result'] = APPRESULT_LOGOUT;
			}
			else{		
				$staff_model = new Staff_Model();	
				$conn_model = new Connect_Model($this->categoryName);
				$member_model = new Member_Model($this->categoryName);
				
				//$websession = $this->request->getVar('websession');
				$uid = $this->session->uid;
				$sess_id = $this->session->session_id;
				$conn = $conn_model->getById($sess_id);
				$ip_addres = $this->request->getIPAddress();
				// writeLog($logHead.$this->categoryName.">>userId=".$uid.">>sessionId=".$sess_id.">>ip=".$ip_addres);

				if(is_null($conn)){
					$this->app_logout($app);
					$arrResult['result'] = APPRESULT_LOGOUT;
					writeLog($logHead.$this->categoryName.">>userId=".$uid.">>session=null");
					
				} else if($conn->sess_mb_uid != $uid){
					$this->app_logout($app);
					$arrResult['result'] = APPRESULT_LOGOUT;
					
				} else{
					$objMember = $member_model->getByUid($conn->sess_mb_uid) ;

					$tmNow = time();
					
					if(is_null($objMember) ){
						$arrResult['result'] = APPRESULT_BLOCK;
					} else if(strtotime($objMember->mb_time_limit) < $tmNow){
						$arrResult['result'] = APPRESULT_EXPIRED;
						writeLog($logHead.$this->categoryName.">>userId=".$uid.">>session=expired");
					} else if($objMember->mb_state_active != PERMIT_OK || 
							!$staff_model->isPermitMember($objMember, $this->categoryId) ||
							$objCat->cat_stop == 1){
						$arrResult['result'] = APPRESULT_BLOCK;
					}  else if($this->categoryName == "luckysheet" && $objMember->mb_prop_1 == 1 ){
						$arrResult['result'] = APPRESULT_BLOCK;
					} else {
						$conn = new \StdClass;
						$conn->sess_id = $sess_id;
						$conn->sess_mb_uid = $uid;
						$conn->sess_pub_addr = $ip_addres;
						$conn->sess_running = intval($this->request->getVar('running'));
						$conn->sess_betting_domain = strval($this->request->getVar('betting_domain'));
						$conn->sess_betting_user = strval($this->request->getVar('betting_user'));
						$conn->sess_betting_real = intval($this->request->getVar('betting'));
						$conn->sess_money_begin = intval($this->request->getVar('money_begin'));
						$conn->sess_money_current = intval($this->request->getVar('money_current'));
						$conn->sess_virtual_begin = intval($this->request->getVar('virtual_begin'));
						$conn->sess_virtual_current = intval($this->request->getVar('virtual_current'));
						$conn->sess_app_title = strval($this->request->getVar('app_title'));
						$conn->sess_app_version = strval($this->request->getVar('app_version'));
						$conn->sess_room_0_level = intval($this->request->getVar('room_0_level'));
						$conn->sess_room_0_earn = intval($this->request->getVar('room_0_money'));
						$conn->sess_room_0_win = intval($this->request->getVar('room_0_win'));
						$conn->sess_room_0_loss = intval($this->request->getVar('room_0_loss'));
						$conn->sess_room_1_level = intval($this->request->getVar('room_1_level'));
						$conn->sess_room_1_earn = intval($this->request->getVar('room_1_money'));
						$conn->sess_room_1_win = intval($this->request->getVar('room_1_win'));
						$conn->sess_room_1_loss = intval($this->request->getVar('room_1_loss'));
						$conn->sess_room_2_level = intval($this->request->getVar('room_2_level'));
						$conn->sess_room_2_earn = intval($this->request->getVar('room_2_money'));
						$conn->sess_room_2_win = intval($this->request->getVar('room_2_win'));
						$conn->sess_room_2_loss = intval($this->request->getVar('room_2_loss'));
						$conn->sess_room_3_level = intval($this->request->getVar('room_3_level'));
						$conn->sess_room_3_earn = intval($this->request->getVar('room_3_money'));
						$conn->sess_room_3_win = intval($this->request->getVar('room_3_win'));
						$conn->sess_room_3_loss = intval($this->request->getVar('room_3_loss'));
						$conn->sess_memo_1 = strval($this->request->getVar('memo_1'));
						$conn->sess_memo_2 = strval($this->request->getVar('memo_2'));
						$conn->sess_memo_3 = strval($this->request->getVar('memo_3'));
						$conn->sess_memo_4 = strval($this->request->getVar('memo_4'));
						$conn->sess_memo_5 = strval($this->request->getVar('memo_5'));
						$conn->sess_prop_1 = strval($this->request->getVar('prop_1'));
						$conn->sess_prop_2 = strval($this->request->getVar('prop_2'));
						$conn->sess_prop_3 = strval($this->request->getVar('prop_3'));
						$conn->sess_prop_4 = strval($this->request->getVar('prop_4'));
						$conn->sess_prop_5 = strval($this->request->getVar('prop_5'));

						$conn_model->updateConnect($conn);
						$update_model = new Update_Model($this->categoryName);
						$lastUpdate = $update_model->getLast();
						
						$arrResult['result'] = APPRESULT_OK;
						$arrResult['remained'] = strval(strtotime($objMember->mb_time_limit) - $tmNow);
						if(!is_null($lastUpdate)) 
							$arrResult['version'] = $lastUpdate->update_version;
						else $arrResult['version'] = "";
						$arrResult['vip'] = strval($objMember->mb_vip);
						
						if($this->categoryName == "luckyfuture" || $this->categoryName == "reantek")		//럭키퓨처
							$arrResult['order'] = $objMember->mb_prop_1;
					}

				}
			}
		}
		echo json_encode($arrResult);
	}

	private function ClearConnect(){
		
		$conn_model = new Connect_Model($this->categoryName);
		$conn_model->deleteLast();
	}

	public function GetNotice($app)
	{

		$cat_model = new Category_Model();
		
		$objCat = $cat_model->getByName($app);
		if(is_null($objCat))
			return;

		$xmlDoc = new \DomDocument('1.0', 'UTF-8');
		$elmNotice = $xmlDoc->createElement('notice', 'notice');
		$xmlDoc->appendChild($elmNotice);

		$updated = "";
		$content = "";
		$this->categoryId = $objCat->cat_id;

		$notice_model = new Notice_Model();
		$objNotice = $notice_model->getNotice($this->categoryId);

		if(!is_null($objNotice)) {
			$updated = $objNotice->notice_updated;
			$content = $objNotice->notice_content;	
			//$content = str_replace("\t", "%%", $content);
			$content = str_replace("\n", "@@", $content);
			//$content = nl2br($content);
			//$content = preg_replace('/r|n/', '@@', $content);

		}
			
		$elmUpdated = $xmlDoc->createElement('updated', $updated);
		$elmNotice->appendChild($elmUpdated);		
		
		$elmContent = $xmlDoc->createElement('content');

		$elmText = $xmlDoc->createTextNode($content);
		$elmContent->appendChild($elmText);

		$elmNotice->appendChild($elmContent);
		
		$xml = $xmlDoc->saveXML();
		
		$this->response->setHeader('Content-type', 'application/xml');

		echo $xml;
		
	}

	
	public function GetNote($app){
		
		$arrResult = [];
		
		$cat_model = new Category_Model();
		
		$objCat = $cat_model->getByName($app);
		if(is_null($objCat)) {
			$arrResult['result'] = APPRESULT_NO_APP;
		} else {

			$this->categoryId = $objCat->cat_id;
			$this->categoryName = $objCat->cat_name;
			
			if(!is_appLogin($this->categoryName))
			{
				$arrResult['result'] = APPRESULT_LOGOUT;
			}
			else{

				$staff_model = new Staff_Model();
				$conn_model = new Connect_Model($this->categoryName);
				$member_model = new Member_Model($this->categoryName);
				
				$uid = $this->session->uid;
				$objMember = $member_model->getByUid($uid) ;
				
				$bPermit = true;
				if(is_null($objMember))
					$arrResult['result'] = APPRESULT_BLOCK;
				else if($objMember->mb_state_active != PERMIT_OK || 
					!$staff_model->isPermitMember($objMember, $this->categoryId) ||
					$objCat->cat_stop == 1){
					$arrResult['result'] = APPRESULT_BLOCK;
				} else if($objCat->cat_name != "luckystock")
					$arrResult['result'] = APPRESULT_BLOCK;
				else{
					
					$note_model = new Note_Model($this->categoryName);
					$objStaff = $staff_model->getByFid($objMember->mb_emp_fid);
					$arrEmpId = $staff_model->getParentIds($objStaff);
					
					$arrData = [];
					$arrNote = $note_model->gets();
					if(!is_null($arrNote)){
						foreach($arrNote as $objNote){
							if($objNote->note_uid == $objStaff->stf_uid){
								$objNote->type = 1;
							} else if(in_array($objNote->note_uid, $arrEmpId) || $objNote->stf_level > LEVEL_COMPANY){
								$objNote->type = 0;
							} else continue;

							array_push($arrData, $objNote);
						}
					}

					$arrResult['result'] = APPRESULT_OK;
					$arrResult['data'] = $arrData;	
				} 
			}
		}
		echo json_encode($arrResult);	


	}

	public function download($app)
	{

		$cat_model = new Category_Model();
		
		$objCat = $cat_model->getByName($app);
		if(is_null($objCat))
			return;
		$this->categoryId = $objCat->cat_id;
		$this->categoryName = $objCat->cat_name;
		$fileName = $this->request->getVar('file');

		$update_model = new Update_Model($this->categoryName);
		$filePath = "";
		$bExist = false;
		$lastUpdate = $update_model->getLast();
		if(!is_null($lastUpdate)) {

			if(strlen($lastUpdate->update_version)>0 && strlen($lastUpdate->update_path)>0){
				$this->downloadPath = DOWNLOADROOT.$objCat->cat_name.DIRECTORY_SEPARATOR;
				$filePath = $this->downloadPath.$lastUpdate->update_version.DIRECTORY_SEPARATOR.$fileName;
			
				if(file_exists($filePath)){
					$bExist = true;
				}
			}					
		}
		
		if($bExist){
			$file = new \CodeIgniter\Files\File($filePath);

			// $mimeType = $file->getMimeType();
			$size     = $file->getSize();

			header("Pragma: no-cache"); 
			header("Expires: 0"); 
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Cache-Control: private', false);
			header("Content-type: application/octet-stream"); 
			header("Content-Disposition: attachment; filename=$fileName"); 
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.$size);
			header('Connection: close');
			readfile("$filePath");
						
		}

	}
	

	public function version($app){

		$cat_model = new Category_Model();
		
		$objCat = $cat_model->getByName($app);
		if(is_null($objCat))
			return;
		$this->categoryId = $objCat->cat_id;
		$this->categoryName = $objCat->cat_name;
		$update_model = new Update_Model($this->categoryName);
		$dirPath = "";
		$bExist = false;
		$lastUpdate = $update_model->getLast();
		if(!is_null($lastUpdate)) {

			if(strlen($lastUpdate->update_version)>0){
				$this->downloadPath = DOWNLOADROOT.$objCat->cat_name.DIRECTORY_SEPARATOR;
				$dirPath = $this->downloadPath.$lastUpdate->update_version.DIRECTORY_SEPARATOR;
			
				if(file_exists($dirPath)){
					$bExist = true;
				}
			}					
		}

		$objVer = new \StdClass;

		$result = "";

		if($bExist){
			$result = $lastUpdate->update_content;

			if(strlen($result) < 1){
				$arrFileInfo = [];
				parseDir($dirPath, $arrFileInfo);
	
				$objVer->version = $lastUpdate->update_version;
	
				$arrFile = [];
				$findDir = $lastUpdate->update_version.DIRECTORY_SEPARATOR;
				foreach($arrFileInfo as $filePath){
	
					$pos = strpos($filePath, $findDir);
					if($pos !== false){
						$handle = fopen($filePath, "r");
						if(filesize($filePath) <= 0)
							continue;
						$contents = fread($handle, filesize($filePath));
						fclose($handle);
						
						$pos += strlen($findDir);
						$fileMd5 = new \StdClass;
						$fileMd5->path = substr($filePath, $pos);
						if(strcmp($fileMd5->path , $lastUpdate->update_path) == 0)
							continue;
						if(substr($fileMd5->path, -4) === ".zip")
							continue;
						$fileMd5->value = md5($contents);
	
						array_push($arrFile, $fileMd5);
					}				
				}

				$objVer->files = $arrFile;

				$result = json_encode($objVer);
				
				$result = str_replace("\\\\", "/", $result);

				$update_model->updateContent($objVer->version, $result);

			}
			
		}

		echo $result;
	}

	public function uploadpy($app)
	{
		$cat_model = new Category_Model();
		
		$objCat = $cat_model->getByName($app);
		if(!is_null($objCat)) 
		{
			$this->categoryId = $objCat->cat_id;
			$this->categoryName = $objCat->cat_name;

			if(is_appLogin($this->categoryName))
			{
				$uid = $this->session->uid;
				
				try
				{
					if(count($_FILES) > 0){
						$iResult = 0;

						$file = reset($_FILES);
						
						$file_name = $file['name'];
						$file_size = $file['size'];
						$file_tmp = $file['tmp_name'];
						// $file_type= $file['type'];
						$arrExt = explode('.', $file_name);
						$file_ext=strtolower(end($arrExt));
						// $file_error = $file['error'];

						$errors = "Upload Error!!";
						
						$extensions= array("exe", "msi", "cmd", "bat", "vbs", "dll", "com");

						if(strlen($file_name) < 1){
							$iResult = 2;
							$errors="File not found";
						}  else if(in_array($file_ext, $extensions) === true){
							$iResult = 3;
							$errors="Extension not allowed";
						} else if($file_size > 524288000){
							$iResult = 4;
							$errors='File size must be exactely 500 MB';
						} else if($file_size < 1){
							$iResult = 4;
							$errors='File size is zero';
						} else if(empty($file_tmp)){
							$iResult = 5;
							$errors='File temporary path is empty';
						}
						
						if($iResult == 0){
							if(!file_exists(DOWNLOADROOT.$objCat->cat_name.DIRECTORY_SEPARATOR)){
								mkdir(DOWNLOADROOT.$objCat->cat_name.DIRECTORY_SEPARATOR);
							}

							$this->settingPath = DOWNLOADROOT.$objCat->cat_name.DIRECTORY_SEPARATOR."setting".DIRECTORY_SEPARATOR;
							if(!file_exists($this->settingPath)){
								mkdir($this->settingPath);
							}

							$filePath = $this->settingPath.$uid;
							if(!file_exists($filePath)){
								mkdir($filePath);
							}
							$filePath .= DIRECTORY_SEPARATOR.$file_name;
							move_uploaded_file($file_tmp, $filePath);
							
							$iResult = 1;					
						}

						if($iResult == 1)
							print "<script> alert('success!!');  </script>";
						else print "<script> alert('".$errors."');  </script>";
						
					}
				}
				catch (\Exception $e)
				{
					$errors = $e->getMessage();
					print "<script> alert('".$errors."'); </script>";
				}
			
				echo view('appsvc/uploadpy', array('cat_name'=>$this->categoryName));
			}
			
		}
	}


	public function RegBettingResult($app){

		$cat_model = new Category_Model();
		
		$objCat = $cat_model->getByName($app);
		if(is_null($objCat)) {
			echo "error url";
			return;
		} 
		
		$this->categoryId = $objCat->cat_id;
		$this->categoryName = $objCat->cat_name;
		$member_model = new Member_Model($this->categoryName);
		$bet_model = new Bet_Model($this->categoryName);

		$uid = $this->request->getVar('mb_appid');
		$domain = $this->request->getVar('mb_domain');
		$guser = $this->request->getVar('mb_guser');
		$gpass = $this->request->getVar('mb_gpass');
		$table = intval($this->request->getVar('table'));
		$result = intval($this->request->getVar('result'));
		$money = intval($this->request->getVar('money'));

		
		if(strlen($uid) < 1 || strlen($guser) < 1 )
			return;

		if($table < 1 || $table > 4)
			return;
		
		$objMember = $member_model->getByUid($uid);
		if(is_null($objMember))
			return;
		
		$objBet = $bet_model->getByTodayUser($uid, $guser);

		
		$bRegMode = false;
		if(is_null($objBet)){
		
			$bRegMode = true;
			$objBet = new \StdClass;
			$objBet->bet_mb_uid = $uid;
			$objBet->bet_date = date("Y-m-d")." 00:00:00";
			$objBet->bet_domain = $domain;
			$objBet->bet_guser = $guser;
			$objBet->bet_gpass = $gpass;
			$objBet->bet_room1_wins = 0;
			$objBet->bet_room2_wins = 0;
			$objBet->bet_room3_wins = 0;
			$objBet->bet_room4_wins = 0;
			$objBet->bet_room1_loss = 0;
			$objBet->bet_room2_loss = 0;
			$objBet->bet_room3_loss = 0;
			$objBet->bet_room4_loss = 0;
			$objBet->bet_room1_earn = 0;
			$objBet->bet_room2_earn = 0;
			$objBet->bet_room3_earn = 0;
			$objBet->bet_room4_earn = 0;
		} else{
		
			$objBet->bet_domain = $domain;
			$objBet->bet_gpass = $gpass;			
		}

		$bAddMode = false;
		switch($table)
		{
			case 1:
				if($result == 1){
					$objBet->bet_room1_wins++;
					$objBet->bet_room1_earn+=$money;
				} else {
					$objBet->bet_room1_loss++;
					if($bAddMode) $objBet->bet_room1_earn+=$money;
					else $objBet->bet_room1_earn-=$money;
				}
				break;
			case 2:
				if($result == 1){
					$objBet->bet_room2_wins++;
					$objBet->bet_room2_earn+=$money;
				} else {
					$objBet->bet_room2_loss++;
					if($bAddMode) $objBet->bet_room2_earn+=$money;
					else $objBet->bet_room2_earn-=$money;
				}
				break;
			case 3:
				if($result == 1){
					$objBet->bet_room3_wins++;
					$objBet->bet_room3_earn+=$money;
				} else {
					$objBet->bet_room3_loss++;
					if($bAddMode) $objBet->bet_room3_earn+=$money;
					else $objBet->bet_room3_earn-=$money;
				}
				break;
			case 4:
				if($result == 1){
					$objBet->bet_room4_wins++;
					$objBet->bet_room4_earn+=$money;
				} else {
					$objBet->bet_room4_loss++;
					if($bAddMode) $objBet->bet_room4_earn+=$money;
					else $objBet->bet_room4_earn-=$money;
				}
				break;
			default:break;			
		}

		if($bRegMode){
			$bet_model->register($objBet);
			echo "register";
		} else{
			$bet_model->modify($objBet);
			echo "update";
		}

		
	}

	//-------------------------------------------



}
