
<link rel="stylesheet" type="text/css" href="/assets/css/note.css" />

<div class="main-container">
    <div class="main-content">
        <p id="category_name" hidden><?=$cat_name?></p>
        <input id="serverUrl" value="<?=$serverUrl?>" type="hidden" >
        
        <!--
        <fieldset>            
            <legend><button class="left-button"  id="connectButton">접속</button>
                <button class="left-button"  id="disconnectButton">해제</button>
            </legend>
            
            <div>
                <label style="margin-left:10px;">접속상태:</label>
                <span id="connectionStatus" style="margin-right:20px;">연결끊김</span>
            </div>
        </fieldset>
        -->
        <fieldset id="messageArea">
            <legend>알림내역                
            </legend>
            <div>
                <button class="left-button"  id="connectButton">접속</button>
                <button class="left-button"  id="disconnectButton">해제</button>
                <label style="margin-left:10px;">접속상태:</label>
                <span id="connectionStatus" style="margin-right:20px;">연결끊김</span>                
            </div>
            <div id="messages"></div>
            <div>
            <button class="left-button" style="margin-left:0px;"  id="clearMessage">전체 삭제</button>
            </div>
        </fieldset>
    
        <fieldset id="requestArea">
            <legend>알림내용</legend>
            <div>
                <textarea id="sendMessage" disabled="disabled" style="margin-bottom: 10px; width: 80%; height: 65px;"></textarea>
            </div>
            <div>
                <button class="left-button"  id="sendButton" disabled="disabled">전송</button> [Ctrl + Enter]
            </div>
        </fieldset>
        
    </div>
</div>


<script src="<?php echo base_url('/assets/js/appsvc/note.js?v=1');?>"></script>