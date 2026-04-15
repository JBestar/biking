<?php 
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Config_Model extends Model {

    private $mDb;
    private $mBuilder;
    private $mTbName = "tbl0_conf";
    private $mTbColumn;

    function __construct()
    {
      
        $this->mDb      = Database::connect();
        $this->mBuilder = $this->mDb->table($this->mTbName);
    }

    public function getConf($conf_id){
        
        try { 
            
            $this->mBuilder->where(['conf_id'=>$conf_id])
                           ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getRow();            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    public function getSiteName(){

        $objConf = $this->getConf(1);
        $strSiteName = "";
        if(!is_null($objConf)){
            $strSiteName = $objConf->conf_content;
        }
        return $strSiteName;
    }

}