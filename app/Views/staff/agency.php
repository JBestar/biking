<div class="main-container">
    <div class="main-content">
        <div class="control-content">
            <a href="/staff/agency_edit/0" class="right-button">총판등록</a>
            <!--<label>총판명 || 아이디</label>-->
            <input type="text" placeholder="총판명 || 아이디"  id="search_txt">
            <button class="left-button" onclick="requestStaff()">총판검색</button>
        </div>
        <Table class="user-table">
            <colgroup>
                <col width="*">
                <col width="*">
                <col width="*">
                <col width="*">
                <col width="*">
                <col width="*">
                <col width="*">
                <col width="*">
            </colgroup>
            <thead>
                <tr>
                    <th>번호</th>
                    <th>아이디</th>
                    <th>총판명</th>
                    <th>등록일</th>
                    <th>접속일</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="tb-data-id">
            
            </tbody>
        </Table>

        
    </div>
</div>

<script src="<?php echo base_url('/assets/js/staff/staff_v1.js');?>"></script>
<script>
    $(document).ready(function(){
        stf_level = 8;
        requestStaff();
    });
</script>