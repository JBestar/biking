<div class="main-sidebar">
    <div style="clear:both; padding-top: 10px;">
    <?php if($stf_level > LEVEL_COMPANY) {  ?>
        <a href="/staff/company" class="<?=$side_item_1?>"><i class="fas fa-user-tie"></i> 본사</a>
    <?php } if($stf_level > LEVEL_AGENCY) {  ?>    
        <a href="/staff/agency" class="<?=$side_item_2?>"><i class="fas fa-user-tie"></i> 총판</a>
    <?php } if($stf_level > LEVEL_EMPLOYEE) {  ?>
        <a href="/staff/employee" class="<?=$side_item_3?>"><i class="fas fa-user-tie"></i> 매장</a>
    <?php } if($stf_level > LEVEL_ADMIN) {  ?>
        <a href="/staff/admin" class="<?=$side_item_4?>"><i class="fab fa-buromobelexperte"></i> 앱관리</a>
    <?php } ?>
    </div>

</div>