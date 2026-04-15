$(document).ready(function(){
    
    requestPage();
 });

 function findHistory(){
     requestPage();
 }
 

 function requestList(){
    requestHistory();
}

 
 function requestPage(){
    var fromDate = $('#bet_time_from').val();
    var toDate = $('#bet_time_to').val();
    var strSearch = $('#search_txt').val();
    var strEmp = $("#stf_emp option:selected").val();
    if(strEmp == undefined)
        return;
    var send_data = { stf_emp:strEmp, search:strSearch, from:fromDate, to:toDate};
    var jsonData = JSON.stringify(send_data);

    var url = getAppUrl("bet_count");
    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: {json_: jsonData},
        success: function(jResult) {
            // console.log(jResult);
            if(jResult.status == "success")
            {
                TotalCount = jResult.data;
                setFirstPage();
                requestHistory();
            } else if(jResult.status == "fail")
            {    
                alert('잘못된 계정정보입니다.');       
                    
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


 function requestHistory(){

    var fromDate = $('#bet_time_from').val();
    var toDate = $('#bet_time_to').val();
    var strSearch = $('#search_txt').val();
    var strEmp = $("#stf_emp option:selected").val();
    if(strEmp == undefined)
        return;
    var nPage = getActivePage();
    if(nPage < 0)
        nPage = 1;
    var send_data = { stf_emp:strEmp, search:strSearch, page:nPage, cntper:CountPerPage,
                    from:fromDate, to:toDate};
    var jsonData = JSON.stringify(send_data);

    var url = getAppUrl("bet_list");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: {json_: jsonData},
        success: function(jResult) {
            // console.log(jResult);
            if(jResult.status == "success")
            {
                showHistory(jResult.data);
            } else if(jResult.status == "fail")
            {    
                alert('잘못된 계정정보입니다.');       
                    
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


 function requestDeleteBet(send_data){

    if(!confirm("초기화하시겠습니까?"))
       return;

    var jsonData = JSON.stringify(send_data);

    var url = getAppUrl("bet_delete");
    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: {json_: jsonData},
        success: function(jResult) {
            // console.log(jResult);
            if(jResult.status == "success")
            {
                requestPage();
            } else if(jResult.status == "fail")
            {    
                alert('잘못된 계정정보입니다.');       
                    
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