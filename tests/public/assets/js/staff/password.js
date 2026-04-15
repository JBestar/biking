
function saveStaff(){
    
    var pwd_cur = $("#stf_pwd").val();
    var pwd_new = $("#stf_pwd_new").val();
    var pwd_ok = $("#stf_pwd_ok").val();
    
    if(pwd_cur.length < 1 ){
        alert("이전 비밀번호를 입력해주십시오.");
        return;
    }
    if(pwd_new.length < 1 || pwd_new != pwd_ok){
        alert("새 비밀번호를 정확히 입력해주십시오.");
        return;
    }

    var jsonData = { "pwd_cur":pwd_cur, "pwd_new":pwd_new};
    
    $.ajax({
        type: "POST",
        dataType: "json",
        url:"/staff/staff_pwd",
        data: jsonData,
        success: function(jResult) {
            //console.log(jResult);
            if(jResult.status == "success")
            {
                alert("비밀번호가 변경되었습니다.");
                backtoStaff();
            } else if(jResult.status == "logout")
            {
                location.replace('/');
            }
            else if(jResult.status == "fail")
            {
                if(jResult.code == 4)   
                    alert("이전비밀번호가 틀립니다.");
                else alert("비밀번호변경이 실패되었습니다.");
            }
        },
        error:function(request,status,error){
            ///console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });

  
    
}

function backtoStaff(){
    location.href = document.referrer;
}