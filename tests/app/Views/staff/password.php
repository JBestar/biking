<div class="main-container">
    <div class="main-content">
        <table class="layout-table">
            <colgroup>
                <col style="width: 150px;" />
                <col style="width: 150px;" />
                <col style="width: 500px;" />
                <col style="width: 200px;" />
            </colgroup>
        
            <tr><td></td>
                <td><label>이전비밀번호:</label></td>
                <td><input type="password" id="stf_pwd"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><label>새비밀번호:</label></td>
                <td><input type="password" id="stf_pwd_new"></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><label>비밀번호확인:</label></td>
                <td><input type="password" id="stf_pwd_ok"></td>
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
                    <button class="form-button" onclick="backtoStaff()">취소</button></td>
                <td></td>
            </tr>
        </table>

    </div>
</div>

<script src="<?php echo base_url('/assets/js/staff/password.js');?>"></script>