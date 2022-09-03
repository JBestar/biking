<?php 
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Staff_Model extends Model {

    
    private $mDb;
    private $mBuilder;
    private $mTbName = "tbl0_staff";
    private $mTbColumn;
    

    function __construct()
    {
        //parent::__construct();        
        $this->mDb      = Database::connect();
        $this->mBuilder = $this->mDb->table($this->mTbName);
        $this->mTbColumn = ['stf_fid', 'stf_uid', 'stf_level', 'stf_emp_fid', 'stf_nickname', 'stf_time_join',
            'stf_time_last', 'stf_color', 'stf_state_active', 'stf_app_01', 'stf_app_02', 'stf_app_03', 'stf_app_04', 
            'stf_app_05', 'stf_app_06', 'stf_app_07', 'stf_app_08', 'stf_app_09', 'stf_app_10', 'stf_app_11', 
            'stf_app_12', 'stf_app_13', 'stf_app_14', 'stf_app_15', 'stf_app_16', 'stf_app_17', 'stf_app_18',
            'stf_app_19', 'stf_app_20', 'stf_app_21', 'stf_app_22', 'stf_app_23', 'stf_app_24', 'stf_app_25',
            'stf_app_26', 'stf_app_27', 'stf_app_28', 'stf_app_29', 'stf_app_30'];
        
    }

    public function getByUid($uid){
        
        try {             
            $this->mBuilder ->select($this->mTbColumn)
                            ->where('stf_uid', $uid)
                            ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getRow();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    public function getByFid($fid){
        
        try {
            if(!in_array('stf_pwd', $this->mTbColumn))
                array_push($this->mTbColumn, 'stf_pwd');     

            $this->mBuilder ->select($this->mTbColumn)
                            ->where('stf_fid', $fid)
                            ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getRow();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    
    public function getByName($stf_name, $stf_fid = 0){
        
        try { 
            $where = "stf_nickname = '".$stf_name."' ";
            if($stf_fid > 0)
                $where.= "AND stf_fid != '".$stf_fid."' ";

            $this->mBuilder ->select($this->mTbColumn)
                            ->where($where)
                            ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getRow();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    public function getAllStaff($level, $search="", $bLow=false){
        
        try { 

            $where = 'stf_level' ;
            $where .= $bLow ? '<' : '=' ;
            $where .= "'".$level."' ";
            if(strlen($search) > 0){
                $where .= " AND (stf_uid LIKE '%".$search."%' OR stf_nickname LIKE '%".$search."%')";
            }

            $this->mBuilder ->select($this->mTbColumn)
                            ->where($where)
                            ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getResult();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    public function getStaffByEmp($emp_fid, $stf_level, $emp_level, $search="", $bLow=false){

        if($emp_level > LEVEL_COMPANY)
        {
            return $this->getAllStaff($stf_level, $search, $bLow);
        } else {
            
            $strTbColum = " stf_fid, stf_uid, stf_level, stf_emp_fid, stf_nickname, stf_time_join, stf_time_last, 
                        stf_color, stf_state_active, stf_app_01, stf_app_02, stf_app_03, stf_app_04, stf_app_05, 
                        stf_app_06, stf_app_07, stf_app_08, stf_app_09, stf_app_10, stf_app_11, stf_app_12, 
                        stf_app_13, stf_app_14, stf_app_15, stf_app_16, stf_app_17, stf_app_18, stf_app_19, stf_app_20,
                        stf_app_21, stf_app_22, stf_app_23, stf_app_24, stf_app_25, stf_app_26, stf_app_27, stf_app_28,
                        stf_app_29, stf_app_30 ";


            $strTbRColum = " r.stf_fid, r.stf_uid, r.stf_level, r.stf_emp_fid, r.stf_nickname, r.stf_time_join, r.stf_time_last,  
                        r.stf_color, r.stf_state_active, r.stf_app_01, r.stf_app_02, r.stf_app_03, r.stf_app_04, 
                        r.stf_app_05, r.stf_app_06, r.stf_app_07, r.stf_app_08, r.stf_app_09, r.stf_app_10, r.stf_app_11,
                        r.stf_app_12, r.stf_app_13, r.stf_app_14, r.stf_app_15, r.stf_app_16, r.stf_app_17, r.stf_app_18,
                        r.stf_app_19, r.stf_app_20, r.stf_app_21, r.stf_app_22, r.stf_app_23, r.stf_app_24, r.stf_app_25,
                        r.stf_app_26, r.stf_app_27, r.stf_app_28, r.stf_app_29, r.stf_app_30 ";


            $strSQL = "WITH RECURSIVE tbmember (".$strTbColum.") AS";
            $strSQL .= " ( SELECT ".$strTbColum." FROM ".$this->mTbName." WHERE stf_emp_fid = '".$emp_fid."'";
            $strSQL .= " UNION ALL SELECT ".$strTbRColum." FROM ".$this->mTbName." r ";
            $strSQL .= " INNER JOIN tbmember ON r.stf_emp_fid = tbmember.stf_fid )";
            $strSQL .= " SELECT * FROM tbmember WHERE ";
            
            $strSQL .= 'stf_level' ;
            $strSQL .= $bLow ? '<' : '=' ;
            $strSQL .= "'".$stf_level."' ";

            if(strlen($search) > 0){
                $strSQL .= "AND (stf_uid LIKE '%".$search."%' OR stf_nickname LIKE '%".$search."%')";
            }
            
            return $this->mDb->query($strSQL)->getResult();
        }

    }

    function register($arrData){
        
        if(!array_key_exists('stf_uid', $arrData))
            return RESULT_ERROR;
        $objStaff = $this->getByUid($arrData['stf_uid']);

        if(!is_null($objStaff))
            return RESULT_EXIST_ID;
        
        if(!array_key_exists('stf_name', $arrData))
            return RESULT_ERROR;
        $objStaff = $this->getByName($arrData['stf_name']);

        if(!is_null($objStaff))
            return RESULT_EXIST_NAME;

        if(!array_key_exists('stf_emp_fid', $arrData))
            return RESULT_ERROR;
        else if($arrData['stf_emp_fid'] > 0){
            $objAdmin = $this->getByFid($arrData['stf_emp_fid']);
            
            if(is_null($objAdmin))
                return RESULT_ERROR;
            else if($objAdmin->stf_level != intval($arrData['stf_level'])+1)
                return RESULT_ERROR;

        } else if($arrData['stf_emp_fid'] == 0){
            if(intval($arrData['stf_level']) != LEVEL_COMPANY)
                return RESULT_ERROR;
        } else return RESULT_ERROR;        

        if(strlen($arrData['stf_pwd']) == 0)
            return RESULT_ERROR;

        $this->mBuilder->set('stf_uid', $arrData['stf_uid']);
        $this->mBuilder->set('stf_pwd', $arrData['stf_pwd']);
        $this->mBuilder->set('stf_level', $arrData['stf_level']);
        $this->mBuilder->set('stf_emp_fid', $arrData['stf_emp_fid']);
        $this->mBuilder->set('stf_nickname', $arrData['stf_name']);
        $this->mBuilder->set('stf_time_join', 'NOW()', false);
        $this->mBuilder->set('stf_color', $arrData['stf_color']);        
        $this->mBuilder->set('stf_state_active', PERMIT_OK);
        
        if($this->mBuilder->insert())   //if success, return true
            return RESULT_OK;
        return RESULT_FAIL;
    }

    function modifyByFid($stf_fid, $arrData){
        
        
        $objStaff = $this->getByName($arrData['stf_name'], $stf_fid);

        if(is_null($objStaff))
            $this->mBuilder->set('stf_nickname', $arrData['stf_name']);
        else return RESULT_EXIST_NAME;

        if(!array_key_exists('stf_emp_fid', $arrData))
            return RESULT_ERROR;
        else if(strlen($arrData['stf_emp_fid']) > 0)
            $this->mBuilder->set('stf_emp_fid', $arrData['stf_emp_fid']);
        else return RESULT_ERROR;

        if(strlen($arrData['stf_pwd']) > 0)
            $this->mBuilder->set('stf_pwd', $arrData['stf_pwd']);
        else return RESULT_ERROR;

        $this->mBuilder->set('stf_color', $arrData['stf_color']);
        
        $this->mBuilder->where('stf_fid', $stf_fid);
        if($this->mBuilder->update())   //if success, return true
            return RESULT_OK;
        return RESULT_FAIL;
    }

    function updateByFid($stf_fid, $arrData){
        
        if(array_key_exists("stf_state_active", $arrData))
            $this->mBuilder->set('stf_state_active', $arrData['stf_state_active']);
        else if(array_key_exists("stf_app_01", $arrData))
            $this->mBuilder->set('stf_app_01', $arrData['stf_app_01']);
        else if(array_key_exists("stf_app_02", $arrData))
            $this->mBuilder->set('stf_app_02', $arrData['stf_app_02']);
        else if(array_key_exists("stf_app_03", $arrData))
            $this->mBuilder->set('stf_app_03', $arrData['stf_app_03']);
        else if(array_key_exists("stf_app_04", $arrData))
            $this->mBuilder->set('stf_app_04', $arrData['stf_app_04']);
        else if(array_key_exists("stf_app_05", $arrData))
            $this->mBuilder->set('stf_app_05', $arrData['stf_app_05']);
        else if(array_key_exists("stf_app_06", $arrData))
            $this->mBuilder->set('stf_app_06', $arrData['stf_app_06']);
        else if(array_key_exists("stf_app_07", $arrData))
            $this->mBuilder->set('stf_app_07', $arrData['stf_app_07']);
        else if(array_key_exists("stf_app_08", $arrData))
            $this->mBuilder->set('stf_app_08', $arrData['stf_app_08']);
        else if(array_key_exists("stf_app_09", $arrData))
            $this->mBuilder->set('stf_app_09', $arrData['stf_app_09']);
        else if(array_key_exists("stf_app_10", $arrData))
            $this->mBuilder->set('stf_app_10', $arrData['stf_app_10']);
        else if(array_key_exists("stf_app_11", $arrData))
            $this->mBuilder->set('stf_app_11', $arrData['stf_app_11']);
        else if(array_key_exists("stf_app_12", $arrData))
            $this->mBuilder->set('stf_app_12', $arrData['stf_app_12']);
        else if(array_key_exists("stf_app_13", $arrData))
            $this->mBuilder->set('stf_app_13', $arrData['stf_app_13']);
        else if(array_key_exists("stf_app_14", $arrData))
            $this->mBuilder->set('stf_app_14', $arrData['stf_app_14']);
        else if(array_key_exists("stf_app_15", $arrData))
            $this->mBuilder->set('stf_app_15', $arrData['stf_app_15']);
        else if(array_key_exists("stf_app_16", $arrData))
            $this->mBuilder->set('stf_app_16', $arrData['stf_app_16']);
        else if(array_key_exists("stf_app_17", $arrData))
            $this->mBuilder->set('stf_app_17', $arrData['stf_app_17']);
        else if(array_key_exists("stf_app_18", $arrData))
            $this->mBuilder->set('stf_app_18', $arrData['stf_app_18']);
        else if(array_key_exists("stf_app_19", $arrData))
            $this->mBuilder->set('stf_app_19', $arrData['stf_app_19']);
        else if(array_key_exists("stf_app_20", $arrData))
            $this->mBuilder->set('stf_app_20', $arrData['stf_app_20']);
        else if(array_key_exists("stf_app_21", $arrData))
            $this->mBuilder->set('stf_app_21', $arrData['stf_app_21']);
        else if(array_key_exists("stf_app_22", $arrData))
            $this->mBuilder->set('stf_app_22', $arrData['stf_app_22']);
        else if(array_key_exists("stf_app_23", $arrData))
            $this->mBuilder->set('stf_app_23', $arrData['stf_app_23']);
        else if(array_key_exists("stf_app_24", $arrData))
            $this->mBuilder->set('stf_app_24', $arrData['stf_app_24']);
        else if(array_key_exists("stf_app_25", $arrData))
            $this->mBuilder->set('stf_app_25', $arrData['stf_app_25']);
        else if(array_key_exists("stf_app_26", $arrData))
            $this->mBuilder->set('stf_app_26', $arrData['stf_app_26']);
        else if(array_key_exists("stf_app_27", $arrData))
            $this->mBuilder->set('stf_app_27', $arrData['stf_app_27']);
        else if(array_key_exists("stf_app_28", $arrData))
            $this->mBuilder->set('stf_app_28', $arrData['stf_app_28']);
        else if(array_key_exists("stf_app_29", $arrData))
            $this->mBuilder->set('stf_app_29', $arrData['stf_app_29']);
        else if(array_key_exists("stf_app_30", $arrData))
            $this->mBuilder->set('stf_app_30', $arrData['stf_app_30']);
        else return false;

        $this->mBuilder->where('stf_fid', $stf_fid);
        return $this->mBuilder->update();   //if success, return true
    }

    public function updateLastTime($uid){

        $this->mBuilder->set('stf_time_last', 'NOW()', false);
        $this->mBuilder->where('stf_uid', $uid);

        return $this->mBuilder->update();   //if success, return true
    }

    function updatePwd($stf_fid, $pwd_new){
        
        $this->mBuilder->set('stf_pwd', $pwd_new);
        
        $this->mBuilder->where('stf_fid', $stf_fid);
        return $this->mBuilder->update();   //if success, return true
    }

    function deleteByFid($stf_fid){
        
        $this->mBuilder->where('stf_fid', $stf_fid);
        return $this->mBuilder->delete();   //if success, return true
    }

    function deleteByFids($arrEmpId){
        
        $this->mBuilder->whereIn('stf_fid', $arrEmpId);

        return $this->mBuilder->delete();   //if success, return true
    }
    
    function deleteAllByEmp($objStaff){
        if(is_null($objStaff))
            return false;
        $arrStaff = $this->getStaffByEmp($objStaff->stf_fid, $objStaff->stf_level, $objStaff->stf_level, "", true);
        if(!is_null($arrStaff)){
            foreach($arrStaff as $objChild){
                $this->deleteByFid($objChild->stf_fid);					
            }
        } 
        return $this->deleteByFid($objStaff->stf_fid);
    }

    public function login($uid, $pwd){
        
        try { 
            $where = "stf_uid = '".$uid."' AND stf_pwd = '".$pwd."' ";

            $this->mBuilder ->select($this->mTbColumn)
                            ->where($where)
                            ->getCompiledSelect(false);
            
            $query = $this->mBuilder->get();
            
            return $query->getRow();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    function getEmpName($objStaff){
        if(is_null($objStaff)) return "";

        //9레벨일때
        if($objStaff->stf_level >= LEVEL_COMPANY) return $objStaff->stf_nickname;

        $strBuf = "";        
        //8레벨일때 
        $objEmp = $this->getByFid($objStaff->stf_emp_fid);
        if(is_null($objEmp)) return "";
        $strBuf = $objEmp->stf_nickname; 
        if($objStaff->stf_level == LEVEL_AGENCY) return $strBuf."::".$objStaff->stf_nickname;

        //7레벨일때 
        $objEmp = $this->getByFid($objEmp->stf_emp_fid);
        if(is_null($objEmp)) return "";
        $strBuf = $objEmp->stf_nickname."::".$strBuf;            
        if($objStaff->stf_level == LEVEL_EMPLOYEE) return $strBuf."::".$objStaff->stf_nickname;

        //그이하일때 
        $objEmp = $this->getByFid($objEmp->stf_emp_fid);
        if(is_null($objEmp)) return "";
        $strBuf = $objEmp->stf_nickname."::".$strBuf;            
        return $strBuf;

    }

    function getEmpIds($objStaff, $category=0)
    {
        $arrEmpId = [];
        if($objStaff->stf_level > LEVEL_EMPLOYEE){
            $arrStaff = $this->getStaffByEmp($objStaff->stf_fid, LEVEL_EMPLOYEE, $objStaff->stf_level);
            if(!is_null($arrStaff)){
                foreach($arrStaff as $objChild){
                    if(!in_array($objChild->stf_fid, $arrEmpId) && isAppOn($objChild, $category)){
                        array_push($arrEmpId, $objChild->stf_fid);
                    }
                }
            } 
        }
        else if($objStaff->stf_level == LEVEL_EMPLOYEE && isAppOn($objStaff, $category)){
            array_push($arrEmpId, $objStaff->stf_fid);
        }
        return $arrEmpId;
    }
    
    function getCategoryStaff($objStaff, $level, $category=0, $search="", $bLow=false){

		$arrEmp = [];
		
        if($objStaff->stf_level > $level) {
            $arrStaff = $this->getStaffByEmp($objStaff->stf_fid, $level, $objStaff->stf_level, $search, $bLow);
            foreach($arrStaff as $objChild){                
                array_push($arrEmp, $objChild); 								
            }
        } else if(!$bLow && $objStaff->stf_level == $level){      
            array_push($arrEmp, $objStaff);
        }
        return  $arrEmp;
    }

    function getNamedStaff($objStaff, $level, $category=0, $search="", $bLow=false){

		$arrEmp = [];
		
        if($objStaff->stf_level > $level) {
            $arrStaff = $this->getStaffByEmp($objStaff->stf_fid, $level, $objStaff->stf_level, $search, $bLow);
            foreach($arrStaff as $objChild){
                
                $objChild->stf_name = $this->getEmpName($objChild);
                array_push($arrEmp, $objChild); 								
            }
        } else if(!$bLow && $objStaff->stf_level == $level){      
            $objStaff->stf_name = $this->getEmpName($objStaff);
            array_push($arrEmp, $objStaff);
        }
        return  $arrEmp;
    }

    function getSortStaffNames($objStaff, $level){

        
		$arrEmp = [];
		$arrName = [];
        $arrStaff = [];

        if($objStaff->stf_level > $level) {
            $arrStaff = $this->getStaffByEmp($objStaff->stf_fid, $level, $objStaff->stf_level);
            
            foreach($arrStaff as $objChild){

                $objChild->stf_name = $this->getEmpName($objChild);

                if(!in_array($objChild->stf_name, $arrName))
                    array_push($arrName, $objChild->stf_name); 								
            }
        } else if($objStaff->stf_level == $level){
            $objStaff->stf_name = $this->getEmpName($objStaff);
            array_push($arrName, $objStaff->stf_name); 	
            array_push($arrStaff, $objStaff); 								
        }

		sort($arrName);

		foreach($arrName as $name){
			foreach($arrStaff as $objChild){
				if($name == $objChild->stf_name){
					$objEmp = new \StdClass;
					$objEmp->stf_fid = $objChild->stf_fid;
					$objEmp->stf_name = $objChild->stf_name;
					if(!in_array($objEmp, $arrEmp))
						array_push($arrEmp, $objEmp);
					break;
				}
			}							
		}

		return $arrEmp;
	}


	function getSortEmpNames($objStaff, $category=0, $bLow=false){

		$arrEmp = [];
		
                  
        if($bLow && $objStaff->stf_level > LEVEL_EMPLOYEE){
            if($objStaff->stf_level > LEVEL_COMPANY)
                $objStaff->stf_level = LEVEL_ADMIN;
            $objEmp = new \StdClass;
            $objEmp->stf_fid = $objStaff->stf_fid;
            $objEmp->stf_name = "전체";
            array_push($arrEmp, $objEmp);
        } 
    
		
        $arrName = [];
        $arrStaff = [];
        if($objStaff->stf_level > LEVEL_EMPLOYEE) {
            $arrStaff = $this->getStaffByEmp($objStaff->stf_fid, $bLow?$objStaff->stf_level:LEVEL_EMPLOYEE, $objStaff->stf_level, "", $bLow);
            
            foreach($arrStaff as $objChild){

                $objChild->stf_name = $this->getEmpName($objChild);

                if(!in_array($objChild->stf_name, $arrName) && isAppOn($objChild, $category))
                    array_push($arrName, $objChild->stf_name); 								
            }
        } else {
            $objEmp = new \StdClass;
            $objEmp->stf_fid = $objStaff->stf_fid;
            $objEmp->stf_name = $this->getEmpName($objStaff);
            array_push($arrEmp, $objEmp);            
        }

		sort($arrName);

		foreach($arrName as $name){
			foreach($arrStaff as $objChild){
				if($name == $objChild->stf_name){
					$objEmp = new \StdClass;
					$objEmp->stf_fid = $objChild->stf_fid;
					$objEmp->stf_name = $objChild->stf_name;
					if(!in_array($objEmp, $arrEmp))
						array_push($arrEmp, $objEmp);
					break;
				}
			}							
		}

		return $arrEmp;
	}

    function isEnableStaff($objAdmin, $objStaff, $bLarge=false){
        $bPermit = false;
        if($objAdmin->stf_level < $objStaff->stf_level)
            $bPermit = false;
        else if($objAdmin->stf_level == $objStaff->stf_level){
            if($bLarge)
                $bPermit = false;    
            else if($objStaff->stf_fid == $objAdmin->stf_fid)
                $bPermit = true;
            else $bPermit = false;
        } else {
            $arrStaff = $this->getStaffByEmp($objAdmin->stf_fid, $objStaff->stf_level, $objAdmin->stf_level);
            foreach($arrStaff as $objChild){
                if($objChild->stf_fid == $objStaff->stf_fid){
                    $bPermit = true;
                    break;
                }
            }
        }			
        return $bPermit;
    }

    function isEnableMember($objStaff, $objMember){
        $bPermit = false;
        if($objStaff->stf_level > LEVEL_EMPLOYEE){
            $arrStaff = $this->getStaffByEmp($objStaff->stf_fid, LEVEL_EMPLOYEE, $objStaff->stf_level);
            foreach($arrStaff as $objChild){
                if($objChild->stf_fid == $objMember->mb_emp_fid)
                    $bPermit = true;					
            }
            
        } else {
            if($objStaff->stf_fid == $objMember->mb_emp_fid)
                $bPermit = true;	
        }	
        return $bPermit;
    }


    function isPermitMember($objMember, $category){
        if(is_null($objMember))
            return false;
        //매장
        $objEmpl = $this->getByFid($objMember->mb_emp_fid);        
        if(is_null($objEmpl))
            return false;
        if($objEmpl->stf_level != LEVEL_EMPLOYEE || $objEmpl->stf_state_active != PERMIT_OK || !isAppOn($objEmpl, $category))
            return false;
    
        //총판
        $objAgen = $this->getByFid($objEmpl->stf_emp_fid);        
        if(is_null($objAgen))
            return false;
        if($objAgen->stf_level != LEVEL_AGENCY || $objAgen->stf_state_active != PERMIT_OK || !isAppOn($objAgen, $category))
            return false;

        //부본사
        $objComp = $this->getByFid($objAgen->stf_emp_fid);        
        if(is_null($objComp))
            return false;
        if($objComp->stf_level != LEVEL_COMPANY || $objComp->stf_state_active != PERMIT_OK || !isAppOn($objComp, $category))
            return false;

        return true;
    }


    function getParentIds($objStaff){
        $arrParentId = [];

        if(is_null($objStaff))
            return $arrParentId;

        if($objStaff->stf_level < LEVEL_EMPLOYEE || $objStaff->stf_level >= LEVEL_COMPANY)
            return $arrParentId;


        //총판
        $objAgen = $this->getByFid($objStaff->stf_emp_fid);        
        if(is_null($objAgen))
            return $arrParentId;
        if($objAgen->stf_level == LEVEL_COMPANY ){
            array_push($arrParentId, $objAgen->stf_uid);
            return $arrParentId;
        }
            
    
        //본사
        $objComp = $this->getByFid($objAgen->stf_emp_fid);        
        if(is_null($objComp))
            return $arrParentId;
        if($objComp->stf_level == LEVEL_COMPANY ){
            array_push($arrParentId, $objComp->stf_uid);
            return $arrParentId;
        }

        return $arrParentId;
    }


    public function changeApp($cat_id1, $cat_id2){

        //UPDATE tbl0_staff SET conf_memo=(@temp:=conf_memo), conf_memo=conf_content, conf_content=@temp;

        if($cat_id1 < 1 || $cat_id1 > 20)
            return false;

        if($cat_id2 < 1 || $cat_id2 > 20)
            return false;
        
        if($cat_id1 == $cat_id2)
            return true;

        $strApp1= $cat_id1 < 10 ? "stf_app_0".$cat_id1 : "stf_app_".$cat_id1 ;
        $strApp2= $cat_id2 < 10 ? "stf_app_0".$cat_id2 : "stf_app_".$cat_id2 ;

        $strSQL = "UPDATE ".$this->mTbName." SET ";
        $strSQL.= $strApp1."=(@tem:=".$strApp1."), ".$strApp1."=".$strApp2.", ".$strApp2."=@tem";
        
        return $this->mDb->simpleQuery($strSQL);    // if success return true 
    
    }


    public function resetApp($category){


        $stf_app = "stf_app_".($category<10?"0".$category:$category);

        $this->mBuilder->set($stf_app, 0);
        
        $this->mBuilder->where('stf_level < ', LEVEL_ADMIN);
        return $this->mBuilder->update();   //if success, return true

    }

}