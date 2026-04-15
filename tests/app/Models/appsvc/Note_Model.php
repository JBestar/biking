<?php 
namespace App\Models\Appsvc;

use CodeIgniter\Model;
use Config\Database;

class Note_Model extends Model {

    private $mDb;
    private $mBuilder;
    private $mTbName ;
    private $mJoinTbName ;
    private $mTbColumn;

    function __construct($category)
    {
        $this->mTbName = "tbl_".strval($category)."_note";
        $this->mJoinTbName = "tbl0_staff";
        $this->mDb      = Database::connect();
        if($this->mDb->tableExists($this->mTbName))
            $this->mBuilder = $this->mDb->table($this->mTbName);

        $this->mTbColumn = ['note_id', 'note_uid', 'note_text', 'note_updated'];

    }
    
    function gets(){
        $this->deleteLast();
        try { 

            if(!in_array('stf_uid', $this->mTbColumn)){
                array_push($this->mTbColumn, 'stf_uid');
                array_push($this->mTbColumn, 'stf_level');
                array_push($this->mTbColumn, 'stf_nickname');                
            }                

            $this->mBuilder ->select($this->mTbColumn)   
                ->join($this->mJoinTbName, $this->mJoinTbName.'.stf_uid = '.$this->mTbName.'.note_uid') 
                ->orderBy('note_id', 'ASC')                        
                ->getCompiledSelect(false);

            $query = $this->mBuilder->get();
            return $query->getResult();


        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }

    function register($stf_uid, $content){

        $this->mBuilder->set('note_uid', $stf_uid);
        $this->mBuilder->set('note_text', $content);
        $this->mBuilder->set('note_updated', 'NOW()', false);

        if($this->mBuilder->insert())   //if success, return true
            return RESULT_OK;
        return RESULT_FAIL;

    }

    function deleteLast(){
        $tmLimit = date("Y-m-d H:i:s", strtotime("-".DAY." seconds", time()));

        $this->mBuilder->where('note_updated < ', $tmLimit);
        return $this->mBuilder->delete();   //if success, return true
    }


}