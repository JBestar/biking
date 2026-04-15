<?php 
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Category_Model extends Model {

    protected $table      = 'tbl0_category';
    protected $primaryKey = 'cat_id';
    protected $returnType = 'object'; 

    protected $allowedFields = ['cat_id', 'cat_name', 'cat_title', 'cat_comment', 'cat_stop', 'cat_order', 'cat_prop_1', 'cat_prop_2']; 

    public function getAll($bOrdById = false){
        
        $orderBy = $bOrdById ? "cat_id" : "cat_order";

        return $this->orderBy($orderBy, 'ASC')
                    ->findAll();
    }

    public function getById($cat_id){
        
        $cat_id = intval($cat_id);
                    
        return $this->where('cat_id', $cat_id)
             ->first();

    }

    public function getByName($cat_name, $cat_id=0){
        
            
        $cat_name = trim(strtolower($cat_name));

        $where = "cat_name = '".$cat_name."' ";
        if($cat_id > 0)
            $where.= "AND cat_id != '".$cat_id."' ";

        return $this->where($where)
                    ->first();
    }

    function deleteById($cat_id){
        
        return $this->where('cat_id', $cat_id)
                    ->delete();
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
            return RESULT_EXIST_ID;

        if(!array_key_exists('cat_title', $arrData))
            return RESULT_ERROR;   
        else if(strlen($arrData['cat_title']) < 1)
            return RESULT_ERROR;  
        
        $data = [
            'cat_id' => $arrData['cat_id'],
            'cat_name' => trim(strtolower($arrData['cat_name'])),
            'cat_title' => trim($arrData['cat_title']),
            'cat_order' => $arrData['cat_order'],
            'cat_prop_1' => $arrData['cat_prop_1'],
        ];
        try {             
            $insertID = $this->insert($data);
            if($insertID >= 0)   //if success, return true
                return RESULT_OK;
        } catch (\Exception $e) {  
            return RESULT_FAIL;
        }
        return RESULT_FAIL;
    }

    function modifyById($cat_id, $arrData, $bCheckExist = true){
        $cat_id = intval($cat_id);
        
        if($bCheckExist){
            $objCat = $this->getByName($arrData['cat_name'], $cat_id);

            if(!is_null($objCat))
                return RESULT_EXIST_NAME;
        }

        $data = [
            'cat_name' => strtolower($arrData['cat_name']),
            'cat_title' => $arrData['cat_title'],
            'cat_prop_1' => $arrData['cat_prop_1'],
        ];
        
        if($this->set($data)->where('cat_id', $cat_id)->update())   //if success, return true
            return RESULT_OK;
        return RESULT_FAIL;
    }

    function updateById($cat_id, $arrData){
        $cat_id = intval($cat_id);
              
        $data = [];
        if(array_key_exists('cat_stop', $arrData))
            $data['cat_stop'] = intval($arrData['cat_stop']);
        else if(array_key_exists('cat_order', $arrData))
            $data['cat_order'] = intval($arrData['cat_order']);
        else 
            return RESULT_FAIL;
        
        if($this->set($data)->where('cat_id', $cat_id)->update())   //if success, return true
            return RESULT_OK;
        return RESULT_FAIL;
            
    }

}
