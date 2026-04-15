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
                <td><label>본사아이디:</label></td>
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
                <td><label>본사닉네임:</label></td>
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
                <td><label>본사색깔:</label></td>
                <td>
                    <?php if(is_null($staff) || empty($staff->stf_color)) {  ?>	
                    <input type = "color" id="stf_color" value="#ffffff">
                    <?php } else {?>
                    <input type = "color" id="stf_color" value="<?=$staff->stf_color?>" >
                    <?php } ?>
                </td>
                <td></td>
            </tr>
            <?php if($stf_level >= LEVEL_MASTER) : ?>  
                <tr>
                    <td></td>
                    <td><label>파라미터:</label></td>
                    <td>
                        <?php if(is_null($staff)) :  ?>	
                            <textarea id="stf_memo" style="width:390px;" rows="5"></textarea>
                        <?php else :?>
                            <textarea id="stf_memo" style="width:390px;" rows="5"><?=$staff->stf_memo?></textarea>
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
                <td ><button class="form-button" onclick="saveStaff()">저장</button>
                    <button class="form-button" onclick="gotoStaff()">취소</button></td>
                <td></td>
            </tr>
        </table>

    </div>
</div>

<script src="<?php echo base_url('/assets/js/staff/staff_edit.js?v=1');?>"></script>


<script>
    stf_level = <?=LEVEL_COMPANY?>;
</script>