<?php 
namespace App\Models\Appsvc;

use CodeIgniter\Model;
use Config\Database;
use CodeIgniter\I18n\Time;

class Member_Model extends Model {

    
    private $mDb;
    private $mBuilder;
    private $mTbName ;
    private $mTbColumn;
    
    function __construct($category)
    {
        //parent::__construct();  
        $this->mTbName = "tbl_".strval($category)."_member";      
        $this->mDb      = Database::connect();
        if($this->mDb->tableExists($this->mTbName))
            $this->mBuilder = $this->mDb->table($this->mTbName);
        $this->mTbColumn = ['mb_fid', 'mb_emp_fid', 'mb_uid', 'mb_nickname', 'mb_phone', 'mb_level', 'mb_vip', 
            'mb_time_join', 'mb_time_last', 'mb_time_limit', 'mb_last_ip', 'mb_state_active', 'mb_memo_1', 
            'mb_memo_2', 'mb_prop_1', 'mb_prop_2'];
        
    }

    public function clear(){
        return $this->mBuilder->truncate();
    }

    public function getByUid($uid){
        
        try {             
            $this->mBuilder ->select($this->mTbColumn)
                            ->where('mb_uid', $uid)
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
            
            if(!in_array('mb_pwd', $this->mTbColumn))
                array_push($this->mTbColumn, 'mb_pwd');

            $this->mBuilder ->select($this->mTbColumn)
                            ->where('mb_fid', $fid)
                            ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getRow();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    public function getByName($mb_name, $mb_fid = 0){
        
        try { 
            $where = "mb_nickname = '".$mb_name."' ";
            if($mb_fid > 0)
                $where.= "AND mb_fid != '".$mb_fid."' ";

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

    function register($arrData){
        
        if(!array_key_exists('mb_uid', $arrData))
            return RESULT_ERROR;
        else if(strlen($arrData['mb_uid']) < 1)
            return RESULT_ERROR;        

        $objMember = $this->getByUid($arrData['mb_uid']);
        if(!is_null($objMember))
            return RESULT_EXIST_ID;
        
        // if(strlen($arrData['mb_name']) > 0){
        //     $objMember = $this->getByName($arrData['mb_name']);
        //     if(!is_null($objMember))
        //         return RESULT_EXIST_NAME;
        // }

        if(!array_key_exists('mb_emp_fid', $arrData))
            return RESULT_ERROR;
        else if(strlen($arrData['mb_emp_fid']) < 1)
            return RESULT_ERROR;        

        if(strlen($arrData['mb_pwd']) == 0)
            return RESULT_ERROR;

        $this->mBuilder->set('mb_emp_fid', $arrData['mb_emp_fid']);
        $this->mBuilder->set('mb_uid', $arrData['mb_uid']);
        $this->mBuilder->set('mb_pwd', $arrData['mb_pwd']);
        $this->mBuilder->set('mb_level', LEVEL_USER);
        if(array_key_exists('mb_phone', $arrData))
            $this->mBuilder->set('mb_phone', $arrData['mb_phone']);
        $this->mBuilder->set('mb_vip', $arrData['mb_vip']);
        if(array_key_exists('mb_name', $arrData))
            $this->mBuilder->set('mb_nickname', $arrData['mb_name']);
        $this->mBuilder->set('mb_time_join', 'NOW()', false);
        $this->mBuilder->set('mb_time_limit', $arrData['mb_time_limit']);        
        $this->mBuilder->set('mb_state_active', PERMIT_OK);
        if(array_key_exists('mb_prop_1', $arrData))
            $this->mBuilder->set('mb_prop_1', $arrData['mb_prop_1']);

        if($this->mBuilder->insert())   //if success, return true
            return RESULT_OK;
        return RESULT_FAIL;
    }


    function modifyByFid($mb_fid, $arrData){
        
        // if(strlen($arrData['mb_name']) > 0){
        //     $objMember = $this->getByName($arrData['mb_name'], $mb_fid);
        //     if(!is_null($objMember))
        //         return RESULT_EXIST_NAME;
        // }
        
        if(!array_key_exists('mb_emp_fid', $arrData))
            return RESULT_ERROR;
        else if(strlen($arrData['mb_emp_fid']) < 1)
            return RESULT_ERROR;            

        if(strlen($arrData['mb_pwd']) < 1)
            return RESULT_ERROR;
        
        $this->mBuilder->set('mb_emp_fid', $arrData['mb_emp_fid']);
        $this->mBuilder->set('mb_nickname', $arrData['mb_name']);
        $this->mBuilder->set('mb_pwd', $arrData['mb_pwd']);
        $this->mBuilder->set('mb_vip', $arrData['mb_vip']);
        $this->mBuilder->set('mb_phone', $arrData['mb_phone']);
        $this->mBuilder->set('mb_time_limit', $arrData['mb_time_limit']);
        if(array_key_exists('mb_prop_1', $arrData))
            $this->mBuilder->set('mb_prop_1', $arrData['mb_prop_1']);

        $this->mBuilder->where('mb_fid', $mb_fid);
        if($this->mBuilder->update())   //if success, return true
            return RESULT_OK;
        return RESULT_FAIL;
    }

    function updateByFid($arrEmpId, $mb_fid, $arrData){
        
        if(array_key_exists("mb_state_active", $arrData))
            $this->mBuilder->set('mb_state_active', $arrData['mb_state_active']);
        else if(array_key_exists("mb_time_limit", $arrData)){
            
            $objMember = $this->getByFid($mb_fid);
            if(is_null($objMember))
                return false;
            $tmLimit = strtotime($objMember->mb_time_limit);
            
            if($tmLimit < 0)
                $tmLimit = time();

            if($arrData['mb_time_limit'] == 1){
                $tmLimit = date("Y-m-d H:i:s", strtotime("+7 days", $tmLimit));
            } else if($arrData['mb_time_limit'] == 2){
                $tmLimit = date("Y-m-d H:i:s", strtotime("+14 days", $tmLimit));
            } else if($arrData['mb_time_limit'] == 3){
                $tmLimit = date("Y-m-d H:i:s", strtotime("+1 month", $tmLimit));
            } else return false;
            
            $this->mBuilder->set('mb_time_limit', $tmLimit);
        } else if(array_key_exists("mb_prop_1", $arrData)){
            $this->mBuilder->set('mb_prop_1', $arrData['mb_prop_1']);
        }
        else return false;

        $this->mBuilder->where('mb_fid', $mb_fid)
                       ->whereIn('mb_emp_fid', $arrEmpId);
        return $this->mBuilder->update();   //if success, return true
    }

    public function updateLastTime($uid){

        $this->mBuilder->set('mb_time_last', 'NOW()', false);
        $this->mBuilder->where('mb_uid', $uid);

        return $this->mBuilder->update();   //if success, return true
    }

    function deleteByFid($arrEmpId, $mb_fid=0){
        if($mb_fid > 0)
            $this->mBuilder->where('mb_fid', $mb_fid);
        $this->mBuilder->whereIn('mb_emp_fid', $arrEmpId);
        return $this->mBuilder->delete();   //if success, return true
    }

    function searchCount($arrEmpId, $search = ""){
        
        try { 
            if(!is_array($arrEmpId))
                return 0;
            if(count($arrEmpId) < 1)
                return 0;

            $where = "mb_emp_fid > '0' ";
            if(strlen($search) > 0){
                $where .= " AND (mb_uid LIKE '%".$search."%' OR mb_nickname LIKE '%".$search."%')";    
            }
            
            $this->mBuilder ->select($this->mTbColumn)                            
                            ->where($where)
                            ->whereIn('mb_emp_fid', $arrEmpId)
                            ->getCompiledSelect(false);

            return $this->mBuilder->countAllResults();
            
        } catch (\Exception $e) {  
            return 0;
        }
        return 0;
    }

    function searchList($arrEmpId, $page, $cntPer = 20, $search = ""){
        
        try { 
            if(!is_array($arrEmpId))
                return NULL;
            if(count($arrEmpId) < 1)
                return NULL;
            if($page < 1)
                return NULL;
            if($cntPer < 1)
                return NULL;

            $where = "mb_emp_fid > '0' ";
            if(strlen($search) > 0){
                $where .= " AND (mb_uid LIKE '%".$search."%' OR mb_nickname LIKE '%".$search."%')";            
            }
            
            if(!in_array('mb_pwd', $this->mTbColumn))
                array_push($this->mTbColumn, 'mb_pwd');

            $this->mBuilder ->select($this->mTbColumn)                            
                            ->where($where)
                            ->whereIn('mb_emp_fid', $arrEmpId)
                            ->orderBy('mb_uid')
                            ->getCompiledSelect(false);

            $query = $this->mBuilder->get($cntPer, $cntPer*($page-1));
            return $query->getResult();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }


    public function login($uid, $pwd){
        
        try { 
            $where = "mb_uid = '".$uid."' AND mb_pwd = '".$pwd."' ";

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

}