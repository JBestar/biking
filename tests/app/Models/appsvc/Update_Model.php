<?php 
namespace App\Models\Appsvc;

use CodeIgniter\Model;
use Config\Database;

class Update_Model extends Model {

    private $mDb;
    private $mBuilder;
    private $mTbName ;
    private $mTbColumn;


    function __construct($category)
    {
        $this->mTbName = "tbl_".strval($category)."_update";
        $this->mDb      = Database::connect();
        if($this->mDb->tableExists($this->mTbName))
            $this->mBuilder = $this->mDb->table($this->mTbName);
        $this->mTbColumn = ['update_id', 'update_version', 'update_path', 'update_date', 'update_content', 'update_author'];

    }

    public function clear(){
        return $this->mBuilder->truncate();
    }

    function getById($id){
        
        try { 
            
            $this->mBuilder ->select($this->mTbColumn)    
                            ->where('update_id', $id)
                            ->getCompiledSelect(false);

            $query = $this->mBuilder->get();
            return $query->getRow();
            
        } catch (\Exception $e) {  
            return NULL;
        }
    }

    function getByVersion($version){
        
        try { 
            
            $this->mBuilder ->select($this->mTbColumn)    
                            ->where('update_version', $version)
                            ->getCompiledSelect(false);

            $query = $this->mBuilder->get();
            return $query->getRow();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    function getLast(){
        
        try { 
            
            $this->mBuilder ->select($this->mTbColumn)    
                            ->orderBy('update_version', 'DESC')
                            ->getCompiledSelect(false);

            $query = $this->mBuilder->get(1, 0);
            return $query->getRow();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    function insertVersion($version, $file, $content, $author){
        
     
        $this->mBuilder->set('update_version', $version);
        $this->mBuilder->set('update_path', $file);
        $this->mBuilder->set('update_date', 'NOW()', false);
        $this->mBuilder->set('update_content', $content);
        $this->mBuilder->set('update_author', $author);

        return $this->mBuilder->insert();   //if success, return true
            
    }

    function updateByVersion($version, $file, $content, $author){
        
     
        $this->mBuilder->set('update_version', $version);
        $this->mBuilder->set('update_path', $file);
        $this->mBuilder->set('update_date', 'NOW()', false);
        $this->mBuilder->set('update_content', $content);
        $this->mBuilder->set('update_author', $author);

        $this->mBuilder->where('update_version', $version);

        return $this->mBuilder->update();   //if success, return true
            
    }

    function updateContent($version, $content){
        
     
        $this->mBuilder->set('update_content', $content);
       
        $this->mBuilder->where('update_version', $version);

        return $this->mBuilder->update();   //if success, return true
            
    }

    function deleteById($update_id){
        
        $this->mBuilder->where('update_id', $update_id);
        return $this->mBuilder->delete();   //if success, return true
    }

    function searchCount(){
        
        try { 
            
            $this->mBuilder ->select($this->mTbColumn)  
                            ->orderBy('update_version', 'DESC')                          
                            ->getCompiledSelect(false);

            return $this->mBuilder->countAllResults();
            
        } catch (\Exception $e) {  
            return 0;
        }
        return 0;
    }

    function searchList($page, $cntPer = 20){
        
        try { 
            if($page < 1)
                return NULL;
            if($cntPer < 1)
                return NULL;

            $this->mBuilder ->select($this->mTbColumn)    
                            ->orderBy('update_version', 'DESC')
                            ->getCompiledSelect(false);

            $query = $this->mBuilder->get($cntPer, $cntPer*($page-1));
            return $query->getResult();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

}