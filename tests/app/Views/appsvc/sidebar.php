<div class="main-sidebar">
    <div style="clear:both; padding-top: 10px;">
        <?php if($stf_level >= LEVEL_EMPLOYEE) {  ?>
            <a href="/<?=$cat_name?>/notice" class="<?=$side_item_1?>"><i class="fas fa-file-alt"></i>공지사항</a>
            <a href="/<?=$cat_name?>/member" class="<?=$side_item_2?>"><i class="fas fa-user"></i>회원관리</a>
            <a href="/<?=$cat_name?>/connect" class="<?=$side_item_3?>"><i class="far fa-clock"></i>실시간접속</a>
            
            <?php if($side_item_8 != "no_view"){ ?>
                <a href="/<?=$cat_name?>/note" class="<?=$side_item_8?>"><i class="fas fa-comment-alt"></i>실시간알림</a>
            <?php } ?>
            
            <a href="/<?=$cat_name?>/bethistory" class="<?=$side_item_4?>"><i class="fas fa-history"></i>베팅내역</a>
            
        <?php } if($stf_level > LEVEL_ADMIN) {  ?>
            <a href="/<?=$cat_name?>/updatehistory" class="<?=$side_item_5?>"><i class="fas fa-upload"></i>업데이트</a>
        <?php } if($stf_level >= LEVEL_ADMIN) {  ?>
            <a href="/<?=$cat_name?>/setting" class="<?=$side_item_6?>"><i class="fas fa-cog"></i>설정보기</a>
        <?php } if($stf_level >= LEVEL_EMPLOYEE) {  ?>
            <a href="/<?=$cat_name?>/downlast" class="<?=$side_item_7?>"><i class="fas fa-download"></i>다운로드</a>
        <?php }  ?>
    </div>

</div>