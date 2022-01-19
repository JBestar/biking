<div class="main-container">
    <div class="main-content">
        <p id="stf_fid" hidden><?=$stf_fid?></p>
        <table class="layout-table">
            <colgroup>
                <col style="width: 200px;" />
                <col style="width: 200px;" />
                <col style="width: 500px;" />
                <col style="width: 200px;" />
            </colgroup>
            
            <tr><td></td>
                <td><label>분류:</label></td>
                <td>
                    <?php if($stf_fid > 0) {  ?>
                        <select id="stf_emp" style="width:215px;" disabled>
                    <?php } else { ?>
                        <select id="stf_emp" style="width:215px;">
                    <?php } ?>
                            <?php foreach ($arrEmp as $objEmp):
                                if(is_null($staff) || ($staff->stf_emp_fid != $objEmp->stf_fid)) {  ?>
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
                <td><label>총판아이디:</label></td>
                <td>
                    <?php if(is_null($staff)) {  ?>	
                    <input type = "text" id="stf_uid">
                    <?php } else {?>
                    <input type = "text" id="stf_uid" value="<?=$staff->stf_uid?>" disabled>
                    <?php } ?>
                    (영문,숫자만 가능)    
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><label>비밀번호:</label></td>
                <td>
                    <?php if(is_null($staff)) {  ?>	
                    <input type = "text" id="stf_pwd">
                    <?php } else {?>
                    <input type = "text" id="stf_pwd" value="<?=$staff->stf_pwd?>" >
                    <?php } ?>    
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><label>총판닉네임:</label></td>
                <td>
                    <?php if(is_null($staff)) {  ?>	
                    <input type = "text" id="stf_name">
                    <?php } else {?>
                    <input type = "text" id="stf_name" value="<?=$staff->stf_nickname?>" >
                    <?php } ?>    
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><label>총판색깔:</label></td>
                <td>
                    <?php if(is_null($staff) || empty($staff->stf_color)) {  ?>	
                    <input type = "color" id="stf_color" value="#ffffff">
                    <?php } else {?>
                    <input type = "color" id="stf_color" value="<?=$staff->stf_color?>" >
                    <?php } ?>
                </td>
                <td></td>
            </tr>
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
                <td ><button class="form-button" onclick="saveStaff()">저장</button>
                    <button class="form-button" onclick="gotoStaff()">취소</button></td>
                <td></td>
            </tr>
        </table>
        
    </div>
</div>

<script src="<?php echo base_url('/assets/js/staff/staff_edit.js');?>"></script>


<script>
    stf_level = 8;
</script>