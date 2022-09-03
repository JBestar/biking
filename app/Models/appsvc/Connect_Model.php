<?php 
namespace App\Models\Appsvc;

use CodeIgniter\Model;
use Config\Database;

class Connect_Model extends Model {

    private $mDb;
    private $mBuilder;
    private $mTbName ;
    private $mJoinTbName ;
    private $mTbColumn;


    function __construct($category)
    {
        $this->mTbName = "tbl_".strval($category)."_session";
        $this->mJoinTbName = "tbl_".strval($category)."_member";
        $this->mDb      = Database::connect();
        if($this->mDb->tableExists($this->mTbName))
            $this->mBuilder = $this->mDb->table($this->mTbName);

        $this->mTbColumn = ['sess_id', 'sess_time_begin', 'sess_time_last', 'sess_hostname', 'sess_browser', 'sess_pub_addr', 
            'sess_emp_fid', 'sess_mb_uid', 'sess_running', 'sess_betting_domain', 'sess_betting_user', 'sess_betting_real', 
            'sess_money_begin', 'sess_money_current', 'sess_virtual_begin', 'sess_virtual_current', 'sess_app_title', 'sess_app_version',
            'sess_room_0_level', 'sess_room_0_earn', 'sess_room_0_win', 'sess_room_0_loss', 'sess_room_1_level', 'sess_room_1_earn', 
            'sess_room_1_win', 'sess_room_1_loss', 'sess_room_2_level', 'sess_room_2_earn', 'sess_room_2_win', 'sess_room_2_loss', 
            'sess_room_3_level', 'sess_room_3_earn', 'sess_room_3_win', 'sess_room_3_loss', 'sess_memo_1', 'sess_memo_2', 
            'sess_prop_1', 'sess_prop_2', 'sess_memo_3', 'sess_memo_4', 'sess_memo_5', 'sess_prop_3', 'sess_prop_4', 'sess_prop_5'  ];

    }

    public function clear(){
        return $this->mBuilder->truncate();
    }
    
    public function getById($sess_id){
        
        try {             
            $this->mBuilder ->select($this->mTbColumn)
                            ->where('sess_id', $sess_id)
                            ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getRow();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }
    
    public function getByUid($sess_mb_uid){
        
        try {             
            $this->mBuilder ->select($this->mTbColumn)
                            ->where('sess_mb_uid', $sess_mb_uid)
                            ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getResult();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }
    
    function register($sess_id, $objMember, $ip_addr){
        
        $objConn = $this->getById($sess_id);
        if(!is_null($objConn)){
            $this->deleteById($objConn->sess_id);
        }

        $this->mBuilder->set('sess_id', $sess_id);
        $this->mBuilder->set('sess_time_begin', 'NOW()', false);
        $this->mBuilder->set('sess_time_last', 'NOW()', false);
        $this->mBuilder->set('sess_pub_addr', $ip_addr);
        $this->mBuilder->set('sess_emp_fid', $objMember->mb_emp_fid);
        $this->mBuilder->set('sess_mb_uid', $objMember->mb_uid);
        
        return $this->mBuilder->insert();   //if success, return true
            
    }

    function updateConnect($conn){        


        $this->mBuilder->set('sess_time_last', 'NOW()', false);
        $this->mBuilder->set('sess_pub_addr', $conn->sess_pub_addr);
        $this->mBuilder->set('sess_running', $conn->sess_running);
        if(strlen($conn->sess_betting_domain) > 0)
            $this->mBuilder->set('sess_betting_domain', $conn->sess_betting_domain);
        if(strlen($conn->sess_betting_user) > 0)
            $this->mBuilder->set('sess_betting_user', $conn->sess_betting_user);
        $this->mBuilder->set('sess_betting_real', $conn->sess_betting_real);
        $this->mBuilder->set('sess_money_begin', $conn->sess_money_begin);
        $this->mBuilder->set('sess_money_current', $conn->sess_money_current);
        $this->mBuilder->set('sess_virtual_begin', $conn->sess_virtual_begin);
        $this->mBuilder->set('sess_virtual_current', $conn->sess_virtual_current);
        if(strlen($conn->sess_app_title) > 0)
            $this->mBuilder->set('sess_app_title', $conn->sess_app_title);
        if(strlen($conn->sess_app_version) > 0)
            $this->mBuilder->set('sess_app_version', $conn->sess_app_version);
        $this->mBuilder->set('sess_room_0_level', $conn->sess_room_0_level);
        $this->mBuilder->set('sess_room_0_earn', $conn->sess_room_0_earn);
        $this->mBuilder->set('sess_room_0_win', $conn->sess_room_0_win);
        $this->mBuilder->set('sess_room_0_loss', $conn->sess_room_0_loss);
        $this->mBuilder->set('sess_room_1_level', $conn->sess_room_1_level);
        $this->mBuilder->set('sess_room_1_earn', $conn->sess_room_1_earn);
        $this->mBuilder->set('sess_room_1_win', $conn->sess_room_1_win);
        $this->mBuilder->set('sess_room_1_loss', $conn->sess_room_1_loss);
        $this->mBuilder->set('sess_room_2_level', $conn->sess_room_2_level);
        $this->mBuilder->set('sess_room_2_earn', $conn->sess_room_2_earn);
        $this->mBuilder->set('sess_room_2_win', $conn->sess_room_2_win);
        $this->mBuilder->set('sess_room_2_loss', $conn->sess_room_2_loss);
        $this->mBuilder->set('sess_room_3_level', $conn->sess_room_3_level);
        $this->mBuilder->set('sess_room_3_earn', $conn->sess_room_3_earn);
        $this->mBuilder->set('sess_room_3_win', $conn->sess_room_3_win);
        $this->mBuilder->set('sess_room_3_loss', $conn->sess_room_3_loss);
        if(strlen($conn->sess_memo_1) > 0)
            $this->mBuilder->set('sess_memo_1', $conn->sess_memo_1);
        if(strlen($conn->sess_memo_2) > 0)
            $this->mBuilder->set('sess_memo_2', $conn->sess_memo_2);
        if(strlen($conn->sess_memo_3) > 0)
            $this->mBuilder->set('sess_memo_3', $conn->sess_memo_3);
        if(strlen($conn->sess_memo_4) > 0)
            $this->mBuilder->set('sess_memo_4', $conn->sess_memo_4);
        if(strlen($conn->sess_memo_5) > 0)
            $this->mBuilder->set('sess_memo_5', $conn->sess_memo_5);
        
        if(strlen($conn->sess_prop_1) > 0)
            $this->mBuilder->set('sess_prop_1', $conn->sess_prop_1);
        if(strlen($conn->sess_prop_2) > 0)
            $this->mBuilder->set('sess_prop_2', $conn->sess_prop_2);
        if(strlen($conn->sess_prop_3) > 0)
            $this->mBuilder->set('sess_prop_3', $conn->sess_prop_3);
        if(strlen($conn->sess_prop_4) > 0)
            $this->mBuilder->set('sess_prop_4', $conn->sess_prop_4);
        if(strlen($conn->sess_prop_5) > 0)
            $this->mBuilder->set('sess_prop_5', $conn->sess_prop_5);

        $this->mBuilder->where('sess_id', $conn->sess_id);
        return $this->mBuilder->update();   //if success, return true
            
    }

    function deleteById($sess_id){

        $this->mBuilder->where('sess_id', $sess_id);
        return $this->mBuilder->delete();   //if success, return true
    }

    function deleteByUid($sess_mb_uid){

        $this->mBuilder->where('sess_mb_uid', $sess_mb_uid);
        return $this->mBuilder->delete();   //if success, return true
    }

    function deleteLast(){
        
        $tmLimit = date("Y-m-d H:i:s", strtotime("-5 minutes", time()));

        $this->mBuilder->where('sess_time_last < ', $tmLimit);
        return $this->mBuilder->delete();   //if success, return true
    }

    function searchCount($arrEmpId, $search = ""){
        
        try { 
            if(!is_array($arrEmpId))
                return 0;
            if(count($arrEmpId) < 1)
                return 0;

            $where = "sess_emp_fid > '0' ";
            if(strlen($search) > 0){
                $where .= " AND (sess_mb_uid LIKE '%".$search."%' OR sess_betting_user LIKE '%".$search."%')";    
            }
            
            $this->mBuilder ->select($this->mTbColumn)                            
                            ->where($where)
                            ->whereIn('sess_emp_fid', $arrEmpId)
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

            $where = "sess_emp_fid > '0' ";
            if(strlen($search) > 0){
                $where .= " AND (sess_mb_uid LIKE '%".$search."%' OR sess_betting_user LIKE '%".$search."%')";            
            }
            
            array_push($this->mTbColumn, 'mb_vip');
            array_push($this->mTbColumn, 'mb_emp_fid');

            $this->mBuilder ->select($this->mTbColumn)    
                            ->join($this->mJoinTbName, $this->mJoinTbName.'.mb_uid = '.$this->mTbName.'.sess_mb_uid')                     
                            ->where($where)
                            ->whereIn('sess_emp_fid', $arrEmpId)
                            ->getCompiledSelect(false);

            $query = $this->mBuilder->get($cntPer, $cntPer*($page-1));
            return $query->getResult();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }


    function getRepeatUid($mb_uid) {
        $arrConn = $this->getByUid($mb_uid);
        if(is_null($arrConn))
            return null;
        if(count($arrConn) < 1)
            return null;
        return $arrConn[0];
    }


}