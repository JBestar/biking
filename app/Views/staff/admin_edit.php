<div class="main-container">
    <div class="main-content">
    	<table class="layout-table">
            <colgroup>
                <col style="width: 200px;" />
                <col style="width: 200px;" />
                <col style="width: 500px;" />
                <col style="width: 200px;" />
            </colgroup>
        
            <tr>
                <td></td>
                <td><label>네임:</label></td>
                <td>
                    <?php if(is_null($objCat)) {  ?>	
                    <input type = "text" id="cat_name">
                    <input type = "text" id="cat_id" hidden>
                    <?php } else {?>
                    <input type = "text" id="cat_name" value="<?=$objCat->cat_name?>" >
                    <input type = "text" id="cat_id" value="<?=$objCat->cat_id?>" hidden>
                    <?php } ?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><label>타이틀:</label></td>
                <td>
                    <?php if(is_null($objCat)) {  ?>	
                    <input type = "text" id="cat_title">
                    <?php } else {?>
                    <input type = "text" id="cat_title" value="<?=$objCat->cat_title?>" >
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
                <td >
                <?php if(is_null($objCat)) {  ?>	
                    <button class="form-button" onclick="createApp()">생성</button>
                    <?php } else {  ?>
                    <button class="form-button" onclick="saveApp()">저장</button>
                    <?php }  ?>
                    <button class="form-button" onclick="gotoAdmin()">취소</button></td>
                <td></td>
            </tr>
        </table>

    </div>
</div>

<script src="<?php echo base_url('/assets/js/staff/admin_edit_v1.js');?>"></script>
