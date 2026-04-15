
$(document).ready(function(){
    
    requestPage();
 });
 

 function findMember(){
     requestPage();
 }

 function requestList(){
    requestMember();
}



 
 function requestPage(){
    var strSearch = $('#search_txt').val();
    var strEmp = $("#stf_emp option:selected").val();
    if(strEmp == undefined)
        return;
    var send_data = { stf_emp:strEmp, search:strSearch};

    var url = getAppUrl("member_count");
    $.ajax({
        type: "POST",
        dataType: "json",
        url:url,
        data: send_data,
        success: function(jResult) {
            // console.log(jResult);
            if(jResult.status == "success")
            {
                TotalCount = jResult.data;
                setFirstPage();
                requestMember();
            } else if(jResult.status == "fail")
            {    
                //alert('잘못된 계정정보입니다.');       
                    
            } else if(jResult.status == "logout")
            {  
                location.replace('/');
            }
        },  
        error:function(request,status,error){
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });
 }


 function requestMember(){
    var strSearch = $('#search_txt').val();
    var strEmp = $("#stf_emp option:selected").val();
    if(strEmp == undefined)
        return;
    var nPage = getActivePage();
    if(nPage < 0)
        nPage = 1;
    var send_data = { stf_emp:strEmp, search:strSearch, page:nPage, cntper:CountPerPage};

    var url = getAppUrl("member_list");
    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: send_data,
        success: function(jResult) {
            // console.log(jResult);
            if(jResult.status == "success")
            {
                showMember(jResult.data, jResult.machine);
            } else if(jResult.status == "fail")
            {    
                //alert('잘못된 계정정보입니다.');       
                    
            } else if(jResult.status == "logout")
            {  
                location.replace('/');
            }
        },  
        error:function(request,status,error){
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });
 }



 
 function requestUpdateMember(jsData){
 
    var jsonData = JSON.stringify(jsData);
    var url = getAppUrl("member_update");
    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: {json_: jsonData},
        success: function(jResult) {
            //console.log(jResult); 
            if(jResult.status == "success")
            {
                requestMember();
            } else if(jResult.status == "fail")
            {
                alert('변경이 실패되었습니다.');            
            } else if(jResult.status == "logout")
            {
                location.replace('/');
            }
        },
        error:function(request,status,error){
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
 
    });
 
 }
 
 function requestDeleteMember(jsData){
    
    if(!confirm("삭제하시겠습니까?"))
       return;
    var jsonData = JSON.stringify(jsData);
    var url = getAppUrl("member_delete");
    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: {json_: jsonData},
        success: function(jResult) {
            //console.log(jResult);    
            if(jResult.status == "success")
            {
                requestMember();
            } else if(jResult.status == "fail")
            {
                alert('삭제가 실패되었습니다.');               
            } else if(jResult.status == "logout")
            {
                location.replace('/');
            }
        },
        error:function(request,status,error){
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
 
    });
 
 }
 