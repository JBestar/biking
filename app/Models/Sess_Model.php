<?php 
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Sess_Model extends Model {

    private $mDb;
    private $mBuilder;
    private $mTbName = "tbl0_staff_session";
    private $mTbColumn;
    private $mJoinTbName = "tbl0_staff";

    function __construct()
    {
        
        $this->mDb      = Database::connect();
        $this->mBuilder = $this->mDb->table($this->mTbName);

        $this->mTbColumn = ['sess_id', 'sess_time_begin', 'sess_time_last', 'sess_stf_uid', 'sess_pub_addr'];

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
            return $e;
        }
        return NULL;
    }
    
    public function getByUid($sess_mb_uid){
        
        try {             
            $this->mBuilder ->select($this->mTbColumn)
                            ->where('sess_stf_uid', $sess_mb_uid)
                            ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getResult();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }
    
    function register($sess_id, $stf_uid, $ip_addr){
        
        $objConn = $this->getById($sess_id);
        if(!is_null($objConn)){
            $this->deleteById($objConn->sess_id);        }

        $this->mBuilder->set('sess_id', $sess_id);
        $this->mBuilder->set('sess_time_begin', 'NOW()', false);
        $this->mBuilder->set('sess_time_last', 'NOW()', false);
        $this->mBuilder->set('sess_stf_uid', $stf_uid);
        $this->mBuilder->set('sess_pub_addr', $ip_addr);
        
        return $this->mBuilder->insert();   //if success, return true
            
    }

    function updateSess($objSess){        


        $this->mBuilder->set('sess_time_last', 'NOW()', false);
        // $this->mBuilder->set('sess_pub_addr', $objSess->sess_pub_addr);
        
        $this->mBuilder->where('sess_id', $objSess->sess_id);
        return $this->mBuilder->update();   //if success, return true
            
    }

    function deleteById($sess_id){

        $this->mBuilder->where('sess_id', $sess_id);
        return $this->mBuilder->delete();   //if success, return true
    }

    function deleteByUid($sess_mb_uid){

        $this->mBuilder->where('sess_stf_uid', $sess_mb_uid);
        return $this->mBuilder->delete();   //if success, return true
    }

    function deleteLast(){
        
        $tmLimit = date("Y-m-d H:i:s", strtotime("-".MINUTE." minutes", time()));

        $this->mBuilder->where('sess_time_last < ', $tmLimit);
        return $this->mBuilder->delete();   //if success, return true
    }


}