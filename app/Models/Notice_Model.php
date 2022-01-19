<?php 
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Notice_Model extends Model {

    
    private $mDb;
    private $mBuilder;
    private $mTbName = "tbl0_notice";
    private $mTbColumn;

    
    function __construct()
    {
               
        $this->mDb      = Database::connect();
        $this->mBuilder = $this->mDb->table($this->mTbName);
        $this->mTbColumn = ['notice_cat', 'notice_updated', 'notice_content', 'notice_author'];
       
    }

    public function getNotice($notice_cat){
        
        try { 
            
            $this->mBuilder->where(['notice_cat'=>$notice_cat])
                           ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getRow();            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    public function updateNotice($notice_cat, $notice_content){
        
        $this->mBuilder->set('notice_updated', 'NOW()', false);   
        $this->mBuilder->set('notice_content', $notice_content);            
        $this->mBuilder->where(['notice_cat'=>$notice_cat]);
                        
        return $this->mBuilder->update();
    }

}