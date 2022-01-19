<?php 
namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Forge_Model extends Model {

    private $mForge;

    function __construct()
    {
        $this->mForge = Database::forge();
    }

    private function dropDbTable($tb_name){
        return $this->mForge->dropTable($tb_name, true);
    }

    private function createDbTable($tb_name, $fields, $primaryKey=""){

        $attributes = ['ENGINE'=>'InnoDB', 'charset'=>'utf8mb4', 'COLLATE'=>'utf8mb4_general_ci'];  
        $this->mForge->addField($fields);
        if(strlen($primaryKey) > 0)
            $this->mForge->addPrimaryKey($primaryKey);

        $objResult = $this->mForge->createTable($tb_name, true, $attributes);
        if(is_null($objResult))
            return false;
        return $objResult->resultID;
    }

    public function deleteApp($category){

        $category = trim($category);

        $tbMember = "tbl_".$category."_member";
        $tbBet = "tbl_".$category."_bet";
        $tbConn = "tbl_".$category."_session";
        $tbUpdate = "tbl_".$category."_update";
        
        if(!$this->dropDbTable($tbMember))
            return false;
        if(!$this->dropDbTable($tbBet))
            return false;
        if(!$this->dropDbTable($tbConn))
            return false;
        if(!$this->dropDbTable($tbUpdate))
            return false;
        return true;    
    }

    public function renameApp($old_category, $new_category){
        $old_category = trim($old_category);
        $new_category = trim($new_category);

        if($old_category == $new_category)
            return false;

        $this->mForge->renameTable("tbl_".$old_category."_member", "tbl_".$new_category."_member");
        $this->mForge->renameTable("tbl_".$old_category."_bet", "tbl_".$new_category."_bet");
        $this->mForge->renameTable("tbl_".$old_category."_session", "tbl_".$new_category."_session");
        $this->mForge->renameTable("tbl_".$old_category."_update", "tbl_".$new_category."_update");
        return true;
        
    }

    public function createApp($category){

        $category = trim($category);

        $tbMember = "tbl_".$category."_member";
        $tbBet = "tbl_".$category."_bet";
        $tbConn = "tbl_".$category."_session";
        $tbUpdate = "tbl_".$category."_update";
        
        $fields = [
            'mb_fid'        => [ 'type' => 'INT', 'auto_increment' => true ],
            'mb_emp_fid'    => [ 'type' => 'INT' ],
            'mb_uid'        => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'mb_pwd'        => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'mb_nickname'   => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'mb_phone'      => [ 'type' => 'VARCHAR', 'constraint' => '30' ],
            'mb_level'      => [ 'type' => 'INT' ],
            'mb_vip'        => [ 'type' => 'INT' ],
            'mb_domain'     => [ 'type' => 'VARCHAR', 'constraint' => '30' ],
            'mb_time_join'  => [ 'type' => 'DATETIME' ],
            'mb_time_last'  => [ 'type' => 'DATETIME' ],
            'mb_time_limit' => [ 'type' => 'DATETIME' ],
            'mb_last_ip'    => [ 'type' => 'VARCHAR', 'constraint' => '60' ],
            'mb_state_active'  => [ 'type' => 'INT' ],
            'mb_state_delete'  => [ 'type' => 'INT' ],
            'mb_memo_1'     => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'mb_memo_2'     => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'mb_prop_1'     => [ 'type' => 'INT' ],
            'mb_prop_2'     => [ 'type' => 'INT' ],
        ];
        $primaryKey = 'mb_fid';
        if(!$this->createDbTable($tbMember, $fields, $primaryKey))
            return false;

        $fields = [
            'bet_fid'    => [ 'type' => 'INT', 'auto_increment' => true ],
            'bet_date'   => [ 'type' => 'DATETIME' ],
            'bet_mb_uid' => [ 'type' => 'VARCHAR', 'constraint' => '30' ],
            'bet_domain' => [ 'type' => 'VARCHAR', 'constraint' => '30' ],
            'bet_guser'  => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'bet_gpass'  => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'bet_room1_wins' => [ 'type' => 'INT' ],
            'bet_room2_wins' => [ 'type' => 'INT' ],
            'bet_room3_wins' => [ 'type' => 'INT' ],
            'bet_room4_wins' => [ 'type' => 'INT' ],
            'bet_room1_loss' => [ 'type' => 'INT' ],
            'bet_room2_loss' => [ 'type' => 'INT' ],
            'bet_room3_loss' => [ 'type' => 'INT' ],
            'bet_room4_loss' => [ 'type' => 'INT' ],
            'bet_room1_earn' => [ 'type' => 'INT' ],
            'bet_room2_earn' => [ 'type' => 'INT' ],
            'bet_room3_earn' => [ 'type' => 'INT' ],
            'bet_room4_earn' => [ 'type' => 'INT' ],
            'bet_memo_1'     => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'bet_memo_2'     => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'bet_prop_1'     => [ 'type' => 'INT' ],
            'bet_prop_2'     => [ 'type' => 'INT' ],
        ];
        $primaryKey = 'bet_fid';
        if(!$this->createDbTable($tbBet, $fields, $primaryKey))
            return false;

        $fields = [
            'sess_id'           => [ 'type' => 'VARCHAR', 'constraint' => '128' ],
            'sess_time_begin'   => [ 'type' => 'DATETIME' ],
            'sess_time_last'    => [ 'type' => 'DATETIME' ],
            'sess_hostname'     => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'sess_browser'      => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'sess_pub_addr'     => [ 'type' => 'VARCHAR', 'constraint' => '60' ],
            'sess_emp_fid'      => [ 'type' => 'INT' ],
            'sess_mb_uid'       => [ 'type' => 'VARCHAR', 'constraint' => '30' ],
            'sess_running'      => [ 'type' => 'INT' ],
            'sess_betting_domain' => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'sess_betting_user' => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'sess_betting_real' => [ 'type' => 'INT' ],
            'sess_money_begin'  => [ 'type' => 'BIGINT' ],
            'sess_money_current' => [ 'type' => 'BIGINT' ],
            'sess_virtual_begin' => [ 'type' => 'BIGINT' ],
            'sess_virtual_current' => [ 'type' => 'BIGINT' ],
            'sess_app_title'    => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'sess_app_version'  => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'sess_room_0_level' => [ 'type' => 'INT' ],
            'sess_room_0_earn'  => [ 'type' => 'INT' ],
            'sess_room_0_win'   => [ 'type' => 'INT' ],
            'sess_room_0_loss'  => [ 'type' => 'INT' ],
            'sess_room_1_level' => [ 'type' => 'INT' ],
            'sess_room_1_earn'  => [ 'type' => 'INT' ],
            'sess_room_1_win'   => [ 'type' => 'INT' ],
            'sess_room_1_loss'  => [ 'type' => 'INT' ],
            'sess_room_2_level' => [ 'type' => 'INT' ],
            'sess_room_2_earn'  => [ 'type' => 'INT' ],
            'sess_room_2_win'   => [ 'type' => 'INT' ],
            'sess_room_2_loss'  => [ 'type' => 'INT' ],
            'sess_room_3_level' => [ 'type' => 'INT' ],
            'sess_room_3_earn'  => [ 'type' => 'INT' ],
            'sess_room_3_win'   => [ 'type' => 'INT' ],
            'sess_room_3_loss'  => [ 'type' => 'INT' ],
            'sess_memo_1'       => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'sess_memo_2'       => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'sess_memo_3'       => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'sess_memo_4'       => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'sess_memo_5'       => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'sess_prop_1'       => [ 'type' => 'INT' ],
            'sess_prop_2'       => [ 'type' => 'INT' ],
            'sess_prop_3'       => [ 'type' => 'INT' ],
            'sess_prop_4'       => [ 'type' => 'INT' ],
            'sess_prop_5'       => [ 'type' => 'INT' ],
        ];
        $primaryKey = 'sess_id';
        if(!$this->createDbTable($tbConn, $fields, $primaryKey))
            return false;
        

        $fields = [
            'update_id'      => [ 'type' => 'INT', 'auto_increment' => true ],
            'update_version' => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'update_path'    => [ 'type' => 'VARCHAR', 'constraint' => '50' ],
            'update_date'    => [ 'type' => 'DATETIME' ],
            'update_content' => [ 'type' => 'TEXT' ],
            'update_author'  => [ 'type' => 'VARCHAR', 'constraint' => '30' ],
        ];
        $primaryKey = 'update_id';
        if(!$this->createDbTable($tbUpdate, $fields, $primaryKey))
            return false;
        return true;
            
    }

    
}
