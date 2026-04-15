<!doctype html>
<html>
	<head>
        <meta charset="utf-8">
	    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		
		<title><?=$site_name?></title>

		<link rel="shortcut icon" type="image/png" href="/favicon.ico"/>
	
        <link rel="stylesheet" href="/assets/css/login.css">
        <!-- JQuery 1.12.4--> 
	    <script src="/assets/js/lib/jquery-1.12.4.min.js"></script>
	
        <script src="/assets/js/util.js?v=2"></script>
        <style>
            <?php if(array_key_exists('login.img', $_ENV)): ?>
                .ibg {
                    background: #000 url(/assets/img/<?=$_ENV['login.img']?>?v=1)no-repeat;
                    background-position: 50%;
                    background-size: cover;
                }
            <?php endif ?>
        </style>
    </head>

    <body>
        <div class="ibg">
            <div class="login_warp">
                <div class="lg_box on" id="login_box">
                    <h2><img src="/assets/img/login_staff.png"></h2>
                    <div class="login">
                        <div class="lg_id">
                            <input type="text" class="input_type" tabindex="1" name="userid" id="userid" placeholder="아이디를 입력하세요." onKeyDown="onEnter();">
                        </div>
                        <div class="lg_pw">
                            <input type="password" class="input_type" tabindex="2" name="userpwd" id="userpwd" placeholder="비밀번호를 입력하세요." onKeyDown="onEnter();">
                        </div>
                    </div>

                    <button class="lg_bt btlogin" tabindex="3" type='button' onclick='login()'>로그인</button>
                    
                    <div class="joinform-footer">©Copyright All Rights Reserved.</div>
                    
                </div>

            </div>
        </div>


    </body>
    <script>
        function onEnter()
        {
            if( window.event.keyCode == 13 ) login();
        }
        
        function login()
        {
            var strId = $('#userid').val();
            var strPwd = $('#userpwd').val();

            if( strId.length == 0 )
            {
                alert('아이디를 입력해주세요');
                return false;
            }

            if( strPwd.length == 0 )
            {
                alert('비밀번호를 입력해주세요');
                return false;
            }

            var send_data = { uid:strId, pwd:strPwd};
            
            $.ajax({
                type: "POST",
                dataType: "json",
                url:"/staff/staff_login",
                data: send_data,
                success: function(jResult) {
                    //console.log(jResult);
                    if(jResult.status == "success")
                    {
                        setCookie('logged', 'yes', 0);
                        location.replace("/");
                    }
                    else if(jResult.status == "fail")
                    {    
                        if(jResult.code == 4)
					        alert('잘못된 계정정보입니다.');
                        else if(jResult.code == 3)
                            alert('차단된 계정입니다.');                                       
                        location.reload();   	
                    }
                },  
                error:function(request,status,error) {
                    //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                }
            });

            return true;
        }

    </script>

</html>