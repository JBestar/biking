<div class="main-container">
    <div class="main-content">
        <p id="category_name" hidden><?=$cat_name?></p>
        <div class="control-content">
            <a href="/<?=$cat_name?>/upload" class="right-button">버전관리</a>        
        </div>
        <Table class="user-table">
            <colgroup>
                <col width="10%">
                <col width="15%">
                <col width="*">
                <col width="15%">
                <col width="15%">
            </colgroup>
			<thead>
				<tr>
					<th >번호</th>
					<th >버전</th>
					<th >업뎃일짜</th>
					<th ></th>
					<th >관리자</th>
										
				</tr>
			</thead>
			<tbody  id="tb-data-id">
                
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



<script src="<?php echo base_url('/assets/js/appsvc/updatehistory.js');?>"></script>