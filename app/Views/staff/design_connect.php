<div class="main-container">
    <div class="main-content">
        <div class="control-content" style="text-align:center; ">
            <?php if(!is_null($objCat)) {  ?>	
            <p style="font-size:20px; width:300px; margin:0 auto; font-weight:bolder; text-align:center; border-bottom:double;  padding:5px 100px;"><?=$objCat->cat_title?> 실시간테블</p>            
            <p id="category_id" hidden><?=$objCat->cat_id?></p>
            <?php } ?>
        </div>
        <Table class="user-table">
            <colgroup>
                <col width="50px">
                <col width="40%">
                <col width="10%">
                <col width="*">
                <col width="*">
            </colgroup>
            <thead>
                <tr>
                    <th>순서</th>
                    <th>필드네임</th>
                    <th>타입</th>
                    <th>설명</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="tb-data-id">
            
            </tbody>
        </Table>
        <table class="layout-table" style="margin-top:10px;">
            <colgroup>
                <col style="width: 200px;" />
                <col style="width: 200px;" />
                <col style="width: 500px;" />
                <col style="width: 200px;" />
            </colgroup>
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
                <td>        
                    <button class="form-button" onclick="saveDesign()">저장</button>
                    <button class="form-button" onclick="gotoAdmin()">취소</button></td>
                <td></td>
            </tr>
        </table>
    </div>
</div>


<script src="<?php echo base_url('/assets/js/staff/design_connect.js');?>"></script>
