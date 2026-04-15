<div class="main-container">
    <div class="main-content">
        <p id="category_id" hidden><?=$cat_id?></p>
        <textarea rows="20" id="notice_content">
<?=$notice->notice_content?></textarea>
        <div>    
        <?php if($level >= LEVEL_ADMIN) {  ?>        
            <button class="left-button" onclick="saveNotice()">공지내용 업데이트</button>
        <?php }  ?>
        </div>
    </div>
</div>



<script src="<?php echo base_url('/assets/js/appsvc/notice.js');?>"></script>
