<?php 
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Category_Model extends Model {

    
    private $mDb;
    private $mBuilder;
    private $mTbName = "tbl0_category";
    private $mTbColumn;

    function __construct()
    {
        //parent::__construct();        
        $this->mDb      = Database::connect();
        $this->mBuilder = $this->mDb->table($this->mTbName);
        $this->mTbColumn = ['cat_id', 'cat_name', 'cat_title', 'cat_comment', 'cat_stop', 'cat_order'];
     
    }

    public function getAll($bOrdById = false){
        
        $orderBy = $bOrdById ? "cat_id" : "cat_order";

        try {             
            $this->mBuilder ->select($this->mTbColumn)
                            ->orderBy($orderBy, 'ASC')
                            ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getResult();
            
        } catch (\Exception $e) {  
            return [];
        }
        return [];
    }

    public function getById($cat_id){
        
        try {     
            $cat_id = intval($cat_id);
                        
            $this->mBuilder ->select($this->mTbColumn)
                            ->where('cat_id', trim($cat_id))
                            ->getCompiledSelect(false);
            $query = $this->mBuilder->get();
            return $query->getRow();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    public function getByName($cat_name, $cat_id=0){
        
        try {
            
            $cat_name = trim(strtolower($cat_name));

            $where = "cat_name = '".$cat_name."' ";
            if($cat_id > 0)
                $where.= "AND cat_id != '".$cat_id."' ";

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

    function deleteById($cat_id){
        
        $cat_id = trim($cat_id);
        $this->mBuilder->where('cat_id', $cat_id);
        return $this->mBuilder->delete();   //if success, return true
    }

    
    function register(&$arrData){
        

        $arrCat = $this->getAll(true);

        if(count($arrCat) < 1){
            $arrData['cat_id'] = 1;
        } else {

            $objCat = end($arrCat);
            if(count($arrCat) == $objCat->cat_id){
                $arrData['cat_id'] = $objCat->cat_id + 1;
            } else {
                $cat_id = 1;
                foreach($arrCat as $objCat){
                    if($objCat->cat_id != $cat_id)
                        break;
                    $cat_id ++;
                }
                $arrData['cat_id'] = $cat_id;
            }
        }

        if($arrData['cat_id'] > 30)
            return RESULT_ERROR; 

        $arrCat = $this->getAll();
        if(count($arrCat) < 1){
            $arrData['cat_order'] = 1;
        } else {
            $objCat = end($arrCat);
            $arrData['cat_order'] = $objCat->cat_order + 1;
        }

        $objCat = $this->getById($arrData['cat_id']);
        if(!is_null($objCat))
            return RESULT_EXIST_ID;

        if(!array_key_exists('cat_name', $arrData))
            return RESULT_ERROR;   
        else if(strlen($arrData['cat_name']) < 1)
            return RESULT_ERROR;  
        else if(!ctype_alnum($arrData['cat_name'])){
            return RESULT_ERROR;  
        }

        $objCat = $this->getByName($arrData['cat_name']);
        if(!is_null($objCat))
            return RESULT_EXIST_NAME;

        if(!array_key_exists('cat_title', $arrData))
            return RESULT_ERROR;   
        else if(strlen($arrData['cat_title']) < 1)
            return RESULT_ERROR;  
        

        $this->mBuilder->set('cat_id', $arrData['cat_id']);
        $this->mBuilder->set('cat_name', trim(strtolower($arrData['cat_name'])));
        $this->mBuilder->set('cat_title', trim($arrData['cat_title']));
        $this->mBuilder->set('cat_order', $arrData['cat_order']);

        if($this->mBuilder->insert())   //if success, return true
            return RESULT_OK;
        return RESULT_FAIL;
    }

    function modifyById($cat_id, $arrData, $bCheckExist = true){
        $cat_id = intval($cat_id);
        
        if($bCheckExist){
            $objCat = $this->getByName($arrData['cat_name'], $cat_id);

            if(!is_null($objCat))
                return RESULT_EXIST_NAME;
        }
        

        $this->mBuilder->set('cat_name', strtolower($arrData['cat_name']));
        $this->mBuilder->set('cat_title', $arrData['cat_title']);
        
        $this->mBuilder->where('cat_id', $cat_id);
        if($this->mBuilder->update())   //if success, return true
            return RESULT_OK;
        return RESULT_FAIL;
    }

    function updateById($cat_id, $arrData){
        $cat_id = intval($cat_id);
              
        if(array_key_exists('cat_stop', $arrData))
            $this->mBuilder->set('cat_stop', intval($arrData['cat_stop']));
        else if(array_key_exists('cat_order', $arrData))
            $this->mBuilder->set('cat_order', intval($arrData['cat_order']));
        else 
            return RESULT_FAIL;
        
        
        $this->mBuilder->where('cat_id', $cat_id);
        if($this->mBuilder->update())   //if success, return true
            return RESULT_OK;
        return RESULT_FAIL;
    }

}
