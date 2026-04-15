$(document).ready(function(){
    
    requestPage();
 });

 function requestList(){
    requestUpdate();
}

 function showHistory(arrUpdate){
    var tHtml = "";
    if(arrUpdate != null && arrUpdate.length > 0){
       
        var curPage = getActivePage();
        var firstIdx = (curPage -1) * CountPerPage;
        for(var idx in arrUpdate){

            tHtml += "<tr>";
            tHtml += "<td>"+(parseInt(idx)+firstIdx+1).toString()+"</td>";
            tHtml += "<td>"+arrUpdate[idx].update_version+"</td>";
            if(arrUpdate[idx].update_date.length > 16)
                tHtml += "<td>"+arrUpdate[idx].update_date.substr(0, 16)+"</td>";
            else tHtml += "<td></td>";
            tHtml += "<td><button name=\"delete\" value=\"" + arrUpdate[idx].update_id + "\">삭제</button></td>";
            tHtml += "<td>"+arrUpdate[idx].update_author+"</td>";
            tHtml += "</tr>";
        }
    }
 
    $("#tb-data-id").html(tHtml);
    addTbEventListner();
    
 }

 function addTbEventListner(){
    var tblBtns = $("#tb-data-id").find("button");
    if(tblBtns == null)
          return;
 
    var jsonData;
    for(var idx = 0; idx < tblBtns.length; idx++){
       
        tblBtns[idx].addEventListener("click", function() {      
    
            if(this.name == "delete") {
                jsonData = { "update_id":this.value};
                requestDeleteUpdate(jsonData);
            } 
        });
 
    }
 }

 
 function requestPage(){
    var url = getAppUrl("update_count");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        success: function(jResult) {
            //console.log(jResult);
            if(jResult.status == "success")
            {
                TotalCount = jResult.data;
                setFirstPage();
                requestUpdate();
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


 function requestUpdate(){
    
    var nPage = getActivePage();
    if(nPage < 0)
        nPage = 1;
    var send_data = { page:nPage, cntper:CountPerPage};

    var url = getAppUrl("update_list");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: send_data,
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



 
 function requestDeleteUpdate(jsData){
    
    if(!confirm("삭제하시겠습니까?"))
       return;
    var jsonData = JSON.stringify(jsData);
 
    var url = getAppUrl("update_delete");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: {json_: jsonData},
        success: function(jResult) {
            // console.log(jResult);
    
            if(jResult.status == "success")
            {
                requestUpdate();
            } else if(jResult.status == "fail")
            {
                alert('삭제가 실패되었습니다.');               
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

