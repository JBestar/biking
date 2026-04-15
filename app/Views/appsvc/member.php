<div class="main-container">
    <div class="main-content">
        <p id="category_name" hidden><?=$cat_name?></p>
        <div class="control-content">            
            <div style="float:left;">
                <select id="stf_emp" style="width: 250px;" onchange="findMember()">
                    <?php foreach ($arrEmp as $objEmp): ?>
                    <option value="<?=$objEmp->stf_fid?>"><?=$objEmp->stf_name?></option>
                    <?php endforeach;?>
                </select>
                <input type="text" placeholder="아이디 || 닉네임" id="search_txt" value="<?=$search_uid?>" style="width: 150px;" >
                <button class="left-button" onclick="findMember()">회원검색</button>
            </div>
            <div style="float:right;">
                <a href="/<?=$cat_name?>/member_oreg" class="right-button">멀티등록</a>
                <a href="/<?=$cat_name?>/member_edit/0" class="right-button">회원등록</a>
            </div>
        </div>
        <div style="margin-top:10px;">
            <Table class="user-table">
                <thead id="tb-head-id">
                    <tr>
                        <th>번호</th>
                        <th>매장</th>
                        <th>아이디 / 비번</th>
                        <th>닉네임</th>
                        <th>등록일</th>
                        <th>접속일</th>
                        <th>허용일짜</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <?php if($cat->cat_prop_1 == STATE_ACTIVE) : ?>
                        <th>기기설정</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="tb-data-id">

                </tbody>
            </Table>
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

<?php if($_ENV['CI_ENVIRONMENT'] == ENV_PRODUCTION) :?>
    <script src="/assets/js/appsvc/member-common.js?v=1"></script>
    <script src="/assets/js/appsvc/member.js?v=2"></script>
<?php else : ?>
    <script src="/assets/js/appsvc/member-common.js?v=<?=time();?>"></script>
    <script src="/assets/js/appsvc/member.js?v=<?=time();?>"></script>
<?php endif ?>