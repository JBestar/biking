<?php 
namespace App\Models\Appsvc;

use CodeIgniter\Model;
use Config\Database;

class Bet_Model extends Model {

    private $mDb;
    private $mBuilder;
    private $mTbName ;
    private $mJoinTbName ;
    private $mTbColumn;


    function __construct($category)
    {
        $this->mTbName = "tbl_".strval($category)."_bet";            
        $this->mJoinTbName = "tbl_".strval($category)."_member";            
        $this->mDb      = Database::connect();
        if($this->mDb->tableExists($this->mTbName))
            $this->mBuilder = $this->mDb->table($this->mTbName);

        $this->mTbColumn = ['bet_fid', 'bet_date', 'bet_mb_uid', 'bet_domain', 'bet_guser', 'bet_room1_wins', 
            'bet_room2_wins', 'bet_room3_wins', 'bet_room4_wins', 'bet_room1_loss', 'bet_room2_loss', 'bet_room3_loss',  
            'bet_room4_loss', 'bet_room1_earn', 'bet_room2_earn', 'bet_room3_earn', 'bet_room4_earn', 'bet_memo_1', 
            'bet_memo_2', 'bet_prop_1', 'bet_prop_2'];

    }

    public function clear(){
        return $this->mBuilder->truncate();
    }
    
    public function getByTodayUser($uid, $guser){
        
        $fromDate = date("Y-m-d");
        $toDate = date("Y-m-d", strtotime("+1 day", time()));

        $where = "bet_mb_uid = '".$uid."' AND bet_guser = '".$guser."' ";
        $where.= "AND bet_date >= '".$fromDate."' AND bet_date < '".$toDate."' "; 

        try {             
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
    
    function register($objBet){

        $this->mBuilder->set('bet_date', $objBet->bet_date);
        $this->mBuilder->set('bet_mb_uid', $objBet->bet_mb_uid);
        $this->mBuilder->set('bet_domain', $objBet->bet_domain);
        $this->mBuilder->set('bet_guser', $objBet->bet_guser);
        $this->mBuilder->set('bet_gpass', $objBet->bet_gpass);
        $this->mBuilder->set('bet_room1_wins', $objBet->bet_room1_wins);
        $this->mBuilder->set('bet_room2_wins', $objBet->bet_room2_wins);
        $this->mBuilder->set('bet_room3_wins', $objBet->bet_room3_wins);
        $this->mBuilder->set('bet_room4_wins', $objBet->bet_room4_wins);
        $this->mBuilder->set('bet_room1_loss', $objBet->bet_room1_loss);
        $this->mBuilder->set('bet_room2_loss', $objBet->bet_room2_loss);
        $this->mBuilder->set('bet_room3_loss', $objBet->bet_room3_loss);
        $this->mBuilder->set('bet_room4_loss', $objBet->bet_room4_loss);
        $this->mBuilder->set('bet_room1_earn', $objBet->bet_room1_earn);
        $this->mBuilder->set('bet_room2_earn', $objBet->bet_room2_earn);
        $this->mBuilder->set('bet_room3_earn', $objBet->bet_room3_earn);
        $this->mBuilder->set('bet_room4_earn', $objBet->bet_room4_earn);

        return $this->mBuilder->insert();
    }

    function modify($objBet){

        $this->mBuilder->set('bet_domain', $objBet->bet_domain);
        $this->mBuilder->set('bet_gpass', $objBet->bet_gpass);
        $this->mBuilder->set('bet_room1_wins', $objBet->bet_room1_wins);
        $this->mBuilder->set('bet_room2_wins', $objBet->bet_room2_wins);
        $this->mBuilder->set('bet_room3_wins', $objBet->bet_room3_wins);
        $this->mBuilder->set('bet_room4_wins', $objBet->bet_room4_wins);
        $this->mBuilder->set('bet_room1_loss', $objBet->bet_room1_loss);
        $this->mBuilder->set('bet_room2_loss', $objBet->bet_room2_loss);
        $this->mBuilder->set('bet_room3_loss', $objBet->bet_room3_loss);
        $this->mBuilder->set('bet_room4_loss', $objBet->bet_room4_loss);
        $this->mBuilder->set('bet_room1_earn', $objBet->bet_room1_earn);
        $this->mBuilder->set('bet_room2_earn', $objBet->bet_room2_earn);
        $this->mBuilder->set('bet_room3_earn', $objBet->bet_room3_earn);
        $this->mBuilder->set('bet_room4_earn', $objBet->bet_room4_earn);

        $this->mBuilder->where('bet_fid', $objBet->bet_fid);
        return $this->mBuilder->update();
    }

    function deleteByUid($mb_uid, $arrData){
        
        if(strlen($mb_uid) < 1)
            return false;

        $where = "bet_mb_uid = '".$mb_uid."' ";
        if(!array_key_exists("to", $arrData))
            return false;
        else if(strlen($arrData['to']) < 1)
            return false;

        $where .= " AND bet_date <= '".$arrData['to']." 23:59:59' ";

        $this->mBuilder->where($where);
        return $this->mBuilder->delete();   //if success, return true
    }

    function searchCount($arrEmpId, $arrRqData, $category_name=''){
        
        try { 
            if(!is_array($arrEmpId))
                return 0;
            if(count($arrEmpId) < 1)
                return 0;

            $where = "bet_fid > '0' ";
            if(strlen($arrRqData['search']) > 0){
                $where .= " AND (bet_mb_uid LIKE '%".$arrRqData['search']."%' OR bet_guser LIKE '%".$arrRqData['search']."%')";    
            }
            if(strlen($arrRqData['from']) > 0){
                $where .= " AND bet_date >= '".$arrRqData['from']."' ";
            }
            if(strlen($arrRqData['to']) > 0){
                $where .= " AND bet_date <= '".$arrRqData['to']." 23:59:59' ";
            }

            $tbColumns = ['bet_mb_uid', 'bet_domain', 'bet_guser', 'mb_emp_fid'];

            $groupBy = ['bet_mb_uid', 'bet_guser'];

            $this->mBuilder ->select($tbColumns)   
                            ->join($this->mJoinTbName, $this->mJoinTbName.'.mb_uid = '.$this->mTbName.'.bet_mb_uid')                         
                            ->where($where)
                            ->whereIn('mb_emp_fid', $arrEmpId)
                            ->groupBy($groupBy)
                            ->getCompiledSelect(false);

            $query = $this->mBuilder->get();
            $arrResult = $query->getResult();
            return count($arrResult);

        } catch (\Exception $e) {  
            return 0;
        }
        return 0;
    }

    function searchList($arrEmpId, $page, $cntPer = 20, $arrRqData, $category_name = ''){
        
        try { 
            if(!is_array($arrEmpId))
                return NULL;
            if(count($arrEmpId) < 1)
                return NULL;
            if($page < 1)
                return NULL;
            if($cntPer < 1)
                return NULL;

            $where = "bet_fid > '0' ";
            if(strlen($arrRqData['search']) > 0){
                $where .= " AND (bet_mb_uid LIKE '%".$arrRqData['search']."%' OR bet_guser LIKE '%".$arrRqData['search']."%')";    
            }
            if(strlen($arrRqData['from']) > 0){
                $where .= " AND bet_date >= '".$arrRqData['from']."' ";
            }
            if(strlen($arrRqData['to']) > 0){
                $where .= " AND bet_date <= '".$arrRqData['to']." 23:59:59' ";
            }

            $tbColumns = ['bet_mb_uid', 'bet_domain', 'bet_guser', 'mb_emp_fid'];

            $groupBy = ['bet_mb_uid', 'bet_guser'];

            $this->mBuilder ->select($tbColumns) 
                            ->selectMin('bet_date', 'bet_date_from')   
                            ->selectMax('bet_date', 'bet_date_to')   
                            ->selectSum('bet_room1_wins')
                            ->selectSum('bet_room2_wins')
                            ->selectSum('bet_room3_wins')
                            ->selectSum('bet_room4_wins')
                            ->selectSum('bet_room1_loss')
                            ->selectSum('bet_room2_loss')
                            ->selectSum('bet_room3_loss')
                            ->selectSum('bet_room4_loss')
                            ->selectSum('bet_room1_earn')
                            ->selectSum('bet_room2_earn')
                            ->selectSum('bet_room3_earn')
                            ->selectSum('bet_room4_earn')
                            ->join($this->mJoinTbName, $this->mJoinTbName.'.mb_uid = '.$this->mTbName.'.bet_mb_uid')                     
                            ->where($where)
                            ->whereIn('mb_emp_fid', $arrEmpId)
                            ->groupBy($groupBy)
                            ->getCompiledSelect(false);

            $query = $this->mBuilder->get($cntPer, $cntPer*($page-1));
            return $query->getResult();
            
        } catch (\Exception $e) {  
            return NULL;
        }
        return NULL;
    }




}