<div class="main-container">
    <div class="main-content">
        <p id="stf_level" hidden><?=$stf_level?></p>
        <p id="category_name" hidden><?=$cat_name?></p>
        <div class="control-content">
            <select id="stf_emp" style="width: 250px;" onchange="findHistory()">
                <?php foreach ($arrEmp as $objEmp): ?>
                <option value="<?=$objEmp->stf_fid?>"><?=$objEmp->stf_name?></option>
                <?php endforeach;?>
            </select>
            <input type="text" placeholder="오토계정" id="search_txt" style="width: 120px;" >
            <input type="date" id="bet_time_from" value="<?php echo date('Y-m-d'); ?>" style="padding: 5px 3px; ">~ 
            <input type="date" id="bet_time_to" value="<?php echo date('Y-m-d'); ?>" style="padding: 5px 3px; ">
            <button class="left-button" onclick="findHistory()">내역보기</button>
            
        </div>
        <Table class="user-table">
            <colgroup>
                <col width="5%">
                <col width="*">
                <col width="*">
                <col width="*">
                <col width="*">
                <col width="*">
                <col width="*">
                <col width="*">
                <col width="*">
                <col width="*">
                <col width="*">
                <col width="*">
                <?php if ($stf_level >= LEVEL_ADMIN) { ?>
                <col width="*">
                <?php } ?>
            </colgroup>
            <thead>
                <tr>
                    <th>번호</th>
                    <th>오토계정</th>
                    <th>일짜</th>
                    <th>1번테블</th>
                    <th>2번테블</th>
                    <th>3번테블</th>
                    <th>4번테블</th>
                    <th>종합</th>
                    <th>1번테블</th>
                    <th>2번테블</th>
                    <th>3번테블</th>
                    <th>4번테블</th>
                    <th>금액합계</th>
                    <?php if ($stf_level >= LEVEL_ADMIN) { ?>
                    <th></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody id="tb-data-id">
                
            </tbody>
        </Table>
        <div style="font-size:15px; font-weight:bolder; padding:10px;">
            <label id="total_earn_sum"></label>
        </div>
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

<script src="<?php echo base_url('/assets/js/appsvc/bethistory-common.js');?>"></script>
<script src="<?php echo base_url('/assets/js/appsvc/bethistory-luckyeval.js');?>"></script>