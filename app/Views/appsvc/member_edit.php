<div class="main-container">
    <div class="main-content">
        <p id="category_name" hidden><?=$cat_name?></p>
        <p id="mb_fid" hidden><?=$mb_fid?></p>
        <table class="layout-table">
            <colgroup>
                <col style="width: 200px;" />
                <col style="width: 200px;" />
                <col style="width: 500px;" />
                <col style="width: 200px;" />
            </colgroup>
            
            <tr><td></td>
                <td><label>매장카테고리:</label></td>
                <td>
                        <select id="mb_emp" style="width:250px;">                    
                            <?php foreach ($arrEmp as $objEmp):
                                if(is_null($member) || ($member->mb_emp_fid != $objEmp->stf_fid)) {  ?>
                                <option value="<?=$objEmp->stf_fid?>"><?=$objEmp->stf_name?></option>
                                <?php } else {?>
                                <option value="<?=$objEmp->stf_fid?>" selected><?=$objEmp->stf_name?></option>
                                <?php }  
                                endforeach;?>
                        </select>
                </td>
                <td></td>
            </tr>
            
            <tr><td></td>
                <td><label>회원아이디:</label></td>
                <td>
                    <?php if(is_null($member)) {  ?>	
                    <input type = "text" id="mb_uid">
                    <?php } else {?>
                    <input type = "text" id="mb_uid" value="<?=$member->mb_uid?>" disabled>
                    <?php } ?>
                    <!--(영문,숫자만 가능)-->    
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><label>비밀번호:</label></td>
                <td>
                    <?php if(is_null($member)) {  ?>	
                    <input type = "text" id="mb_pwd">
                    <?php } else {?>
                    <input type = "text" id="mb_pwd" value="<?=$member->mb_pwd?>" >
                    <?php } ?>    
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><label>회원닉네임:</label></td>
                <td>
                    <?php if(is_null($member)) {  ?>	
                    <input type = "text" id="mb_name">
                    <?php } else {?>
                    <input type = "text" id="mb_name" value="<?=$member->mb_nickname?>" >
                    <?php } ?>    
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><label>핸드폰번호:</label></td>
                <td>
                    <?php if(is_null($member)) {  ?>	
                    <input type = "text" id="mb_phone">
                    <?php } else {?>
                    <input type = "text" id="mb_phone" value="<?=$member->mb_phone?>" >
                    <?php } ?>    
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><label>VIP등급:</label></td>
                <td>
                        <?php if($stf_level >= LEVEL_AGENCY) { ?>
                            <select id="mb_vip" style="width:165px;">
                        <?php } else { ?>
                            <select id="mb_vip" style="width:165px;" disabled>
                        <?php } ?>
                        <?php if(is_null($member) || ($member->mb_vip != 0)) {  ?>
                            <option value="0">일반</option>
                            <?php } else {?>
                            <option value="0" selected>일반</option>
                        <?php }
                        for ($level = 1 ; $level < 10 ; $level ++):
                            if(is_null($member) || ($member->mb_vip != $level)) {  ?>
                            <option value="<?=$level?>">VIP <?=$level?></option>
                            <?php } else {?>
                            <option value="<?=$level?>" selected>VIP  <?=$level?></option>
                            <?php }  
                        endfor;?>
                    </select>
                    <label style="font-size: 14px;">등급</label>   
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><label>허가기간:</label></td>
                <td>
                    <?php if(is_null($member)) {  ?>	
                    <input type = "date" id="mb_time_limit" value="<?php echo date('Y-m-d'); ?>" style="font-size: 14px; width:150px;">
                    <?php } else {?>
                    <input type = "date" id="mb_time_limit" value="<?=substr($member->mb_time_limit, 0, 10)?>" style="font-size: 14px; width:150px;">
                    <?php } ?> 
                    <label style="font-size: 14px;">일까지</label>      
                </td>
                <td></td>
            </tr>
            <?php if($stf_level > LEVEL_ADMIN) : ?>  
            <tr>
                <td></td>
                <td><label>파라미터:</label></td>
                <td>
                    <?php if(is_null($member)) :  ?>	
                        <textarea id="mb_memo_2" style="width:390px;" rows="5"></textarea>
                    <?php else :?>
                        <textarea id="mb_memo_2" style="width:390px;" rows="5"><?=$member->mb_memo_2?></textarea>
                    <?php endif ?>   
                </td>
                <td></td>
            </tr>
            <?php endif ?>
            <tr>
                <td></td>
                <td colspan="2">
                    <hr />
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>          
                <td></td>       
                <td ><button class="form-button" onclick="saveMember()">저장</button>
                    <button class="form-button" onclick="cancelMember()">취소</button></td>
                <td></td>
            </tr>
        </table>
        
    </div>
</div>

<script src="<?php echo base_url('/assets/js/appsvc/member_edit-common.js');?>"></script>
<script src="<?php echo base_url('/assets/js/appsvc/member_edit.js?v=1');?>"></script>
