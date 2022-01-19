<div class="main-container">
    <div class="main-content">
    <p id="category_name" hidden><?=$cat_name?></p>
    <Table style="width:100%;">
        <colgroup>
            <col width="40%">
            <col width="50px">
            <col width="*">
        </colgruop>
        <tr>
            <td style="vertical-align:top;">

                <!-- Account Table -->
                <div class="control-content" style="text-align:center; border-bottom:double; padding-bottom:5px;">
                    <p style="font-size:16px; width:100%; text-align:center;">계정리스트</p>
                    <input type="text" placeholder="계정" id="search_txt" style="width: 150px;" >
                    <button class="left-button" onclick="requestAccountPage()">검색</button>
                </div>
                <Table class="user-table">
                    <colgroup>
                        <col width="50px">
                        <col width="*">
                        <col width="100px">
                    </colgruop>
                    <thead>
                        <tr>
                            <th>번호</th>
                            <th>계정</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tb1-data-id">

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


            </td>
            <td></td>
            <td style="vertical-align:top;">

                <!-- Setting Table -->
                <div class="control-content" style="border-bottom:double; padding-top:20px;">
                    <p style="font-size:16px; width:100%; text-align:center;">계정설정파일리스트</p>                    
                </div>
                <Table class="user-table">
                    <colgroup>
                        <col width="50px">
                        <col width="*">
                        <col width="*">
                        <col width="*">
                        <col width="*">
                        <col width="*">
                    </colgruop>
                    <thead>
                        <tr>
                            <th>번호</th>
                            <th>계정</th>
                            <th>설정파일</th>
                            <th>수정일짜</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tb2-data-id">

                    </tbody>
                </Table>
                <div class="list-page-div" id="list2-page" style="display:none;">
                    <div class="pagination">
                        <button class="list-page-button" id="page-prev2" onclick="prevPage_2()"><<</button>
                        <div class="pagination-div" id="pagination2-num">
                            <!--
                            <button class="active">1</button>
                            <button class="">2</button>
                            -->
                        </div>
                        <button class="list-page-button" id="page-next2" onclick="nextPage_2()">>></button>
                    </div>
                </div>



            </td>
        </tr>

    </Table>


    </div>
</div>


<script src="<?php echo base_url('/assets/js/appsvc/setting.js');?>"></script>