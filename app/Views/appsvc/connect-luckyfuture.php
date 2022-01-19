<div class="main-container">
    <div class="main-content">
        <p id="category_name" hidden><?=$cat_name?></p>
        <div class="control-content">
            <select id="stf_emp" style="width: 250px;" onchange="findConnector()">
                <?php foreach ($arrEmp as $objEmp): ?>
                <option value="<?=$objEmp->stf_fid?>"><?=$objEmp->stf_name?></option>
                <?php endforeach;?>
            </select>
            <input type="text" placeholder="아이디 || 캐릭" id="search_txt" style="width: 150px;" >
            <button class="left-button" onclick="findConnector()">검색</button>
            
        </div>
        <Table class="user-table">
            
            <thead>
                <tr>
                    <th>번호</th>
                    <th>오토</th>
                    <th>유저정보</th>
                    <th>실제금액상태</th>
                    <th>가상금액상태</th>
                    <th>1번테블</th>
                    <th>2번테블</th>
                    <th>3번테블</th>
                    <th>4번테블</th>
                    <th>시작시간</th>
                    <th>제품정보</th>
                    <th>아이피</th>
                </tr>
            </thead>
            <tbody id="tb-data-id">
                
            </tbody>
        </Table>
        <div class="list-page-div" id="list-page" style="display:none;">
            <div class="pagination">
                <button class="list-page-button" id="page-prev" onclick="prevPage()"><<</button>
                <div class="pagination-div" id="pagination-num">
                    <!--
                    <button class="active">1</button>
                    <button class="">2</button>
                    -->
                </div>
                <button class="list-page-button" id="page-next" onclick="nextPage()">>></button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('/assets/js/appsvc/connect-common.js');?>"></script>
<script src="<?php echo base_url('/assets/js/appsvc/connect-luckyfuture.js');?>"></script>