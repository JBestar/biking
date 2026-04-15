

function requestModifyMember(objMember){

    var jsonData = JSON.stringify(objMember);
    
    if(!confirm("저장하시겠습니까?"))
        return;

    var url = getAppUrl("member_modify");
    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: {json_: jsonData},
        success: function(jResult) {
            //console.log(jResult);
            if(jResult.status == "success")
            {
                location.replace(getAppUrl("member"));
            } else if(jResult.status == "logout")
            {
                location.replace('/');
            }
            else if(jResult.status == "fail")
            {
                if(jResult.code == 6)   
                    alert("중복된 닉네임입니다.");
                else alert("수정이 실패되었습니다.");
            }
        },
        error:function(request,status,error){
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });

}

function requestCreateMember(objMember){

    var jsonData = JSON.stringify(objMember);
    
    if(!confirm("저장하시겠습니까?"))
        return;

    var url = getAppUrl("member_create");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: {json_: jsonData},
        success: function(jResult) {
            //console.log(jResult);
            if(jResult.status == "success")
            {
                location.replace(getAppUrl("member"));
            } else if(jResult.status == "logout")
            {
                location.replace('/');
            }
            else if(jResult.status == "fail")
            {
                if(jResult.code == 5)
                    alert("중복된 아이디입니다.");
                else if(jResult.code == 6)   
                    alert("중복된 닉네임입니다.");
                else alert("등록이 실패되었습니다.");
            }
        },
        error:function(request,status,error){
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
    
}

function cancelMember(){
    
    location.href = getAppUrl("member");
}