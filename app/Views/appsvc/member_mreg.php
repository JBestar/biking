<div class="main-container">
    <div class="main-content">
        <p id="category_name" hidden><?=$cat_name?></p>
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
                            <?php foreach ($arrEmp as $objEmp):?>
                                <option value="<?=$objEmp->stf_fid?>"><?=$objEmp->stf_name?></option>
                            <?php endforeach;?>
                        </select>
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><label>회원닉네임:</label></td>
                <td>
                    <input type = "text" id="mb_name">
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><label>VIP등급:</label></td>
                <td>
                <?php if($stf_level >=LEVEL_COMPANY) { ?>
                    <select id="mb_vip" style="width:165px;">
                <?php } else { ?>
                    <select id="mb_vip" style="width:165px;" disabled>
                <?php } ?>
                            <option value="0">일반</option>
                        <?php for ($level = 1 ; $level < 10 ; $level ++):?>
                            <option value="<?=$level?>">VIP <?=$level?></option>
                        <?php endfor;?>
                    </select>
                    <label style="font-size: 14px;">등급</label>   
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><label>허가기간:</label></td>
                <td>
                    <input type = "date" id="mb_time_limit" value="<?php echo date('Y-m-d'); ?>" style="font-size: 14px; width:150px;">
                    <label style="font-size: 14px;">일까지</label>      
                </td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><label>생성개수:</label></td>
                <td>
                    <input type = "number" id="mb_count" min="0" max="100" step="1" style="font-size: 14px; width:150px;">
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
                <td>
                    <button class="form-button" onclick="autoCreate()">자동생성</button>
                </td>       
                <td ><button class="form-button" onclick="saveMember()">저장</button>
                    <button class="form-button" onclick="cancelMember()">취소</button></td>
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
                <td colspan="2">
                    <Table class="user-table" id="tb-id" style="display:none;">
                        <colgroup>
                            <col width="200px">
                            <col width="100px">
                        </colgruop>
                        <thead>
                            <tr>
                                <th>아이디</th>
                                <th>비번</th>
                            </tr>
                        </thead>
                        <tbody id="tb-data-id">

                        </tbody>
                    </Table>
                </td>
                <td></td>
            </tr>
        </table>
        
    </div>
</div>


<script src="<?php echo base_url('/assets/js/appsvc/member_mreg.js');?>"></script>
