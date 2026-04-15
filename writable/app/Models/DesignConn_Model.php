<?php 
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class DesignConn_Model extends Model {

    
    private $mDb;
    private $mBuilder;
    private $mTbName = "tbl0_design_session";
    private $mTbColumn;

    function __construct()
    {
      
        $this->mDb      = Database::connect();
        $this->mBuilder = $this->mDb->table($this->mTbName);
    }


    public function getByCatId($cat_id){
        
        try { 
            
            $this->mBuilder->where('cat_id', $cat_id)
                           ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getRow();            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    public function modifyByCatId($cat_id, $arrField){
        if(!is_array($arrField) || count($arrField) < 1)
            return RESULT_FAIL;

        foreach($arrField as $field){
            if(!array_key_exists("key", $field))
                continue;

            $value = $field['hidden'] == 1 ? "$":"#";
            $value.= $field['order']."@".$field['value'];
            $this->mBuilder->set($field['key'], $value);
        }
        $this->mBuilder->where('cat_id', $cat_id);
        if($this->mBuilder->update())   //if success, return true
            return RESULT_OK;
        return RESULT_FAIL;

    }



    public function changeApp($cat_id1, $cat_id2){

        //UPDATE tbl0_design_session SET cat_id = CASE cat_id WHEN 15 THEN 14 WHEN 14 THEN 15 END WHERE cat_id IN (14, 15)

        if($cat_id1 < 1 || $cat_id1 > 20)
            return false;

        if($cat_id2 < 1 || $cat_id2 > 20)
            return false;
        
        if($cat_id1 == $cat_id2)
            return true;

        $strSQL = "UPDATE ".$this->mTbName." SET ";
        $strSQL.= "cat_id = CASE cat_id WHEN ".$cat_id1." THEN ".$cat_id2;
        $strSQL.= " WHEN ".$cat_id2." THEN ".$cat_id1;
        $strSQL.= " END WHERE cat_id IN (".$cat_id1.", ".$cat_id2.") ";
        
        return $this->mDb->simpleQuery($strSQL);    // if success return true 
    

    }





}