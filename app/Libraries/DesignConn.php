<?php namespace App\Libraries;
use App\Models\DesignConn_Model;

class DesignConn
{
    protected $design_model;

    public function __construct() {
        $this->design_model = new DesignConn_Model();
    }

    public function sortedFdValue($cat_id){
        $arrField = $this->sortedFields($cat_id);
        $arrFdValue = [];
        foreach($arrField as $field){
            array_push($arrFdValue, $field['value']);
        }
        return $arrFdValue;
    }

    public function sortedFdKey($cat_id){
        $arrField = $this->sortedFields($cat_id);
        $arrFdValue = [];
        foreach($arrField as $field){
            array_push($arrFdValue, $field['key']);
        }
        return $arrFdValue;
    }

    public function sortedFields($cat_id, $needAll = false){

        $arrField = [];
        $orders = [];

        $objDesign = $this->design_model->getByCatId($cat_id);
        if(is_null($objDesign))
            return $arrField;

        foreach($objDesign as $key=>$value){
            $field = $this->parseField($key, $value, false);
            if(!is_null($field)){
                array_push($arrField, $field);
                array_push($orders, $field['order']);
            } else if($needAll){
                $field = $this->parseField($key, $value, true);
                if(!is_null($field)){
                    array_push($arrField, $field);
                    array_push($orders, $field['order']);
                }   
            }
                
        }
        
        array_multisort($orders, SORT_ASC, $arrField);
        
        return $arrField;

    }


    public function parseField($fdKey, $fdStr, $bHidden){
        $lstart = $bHidden?"$":"#";
        $lend = "@";

        $posStart = strpos($fdStr, $lstart);
        if($posStart === false) return null; 
        $posEnd = strpos($fdStr, $lend, $posStart);
        if($posEnd === false) return null; 

        $posStart ++;
        $fdOrder = substr($fdStr, $posStart, $posEnd - $posStart);
        $fdValue = substr($fdStr, $posEnd+1);

        $field['key'] = $fdKey;
        $field['order'] = $fdOrder;
        $field['value'] = $fdValue;
        $field['hidden'] = $bHidden?"1":"0";
        return $field;
    }

}