

 var TotalCount2 = 0;
 var SelAccount = "";
 
 $(document).ready(function(){
    CountPerPage = 15;
    ViewPage = 5;

    requestAccountPage();
 
});

function requestList(){
    requestAccount();
}


 function showAccount(arrAccount){
    var tHtml = "";
    if(arrAccount != null && arrAccount.length > 0){
       
        var curPage = getActivePage();
        var firstIdx = (curPage -1) * CountPerPage;
        for(var idx in arrAccount){
            
            tHtml += "<tr>";
            tHtml += "<td>"+(parseInt(idx)+firstIdx+1).toString()+"</td>";
            tHtml += "<td>"+arrAccount[idx]+"</td>";
            tHtml += "<td><button name=\"view\" value=\"" + arrAccount[idx] + "\">보기</button></td>";
            tHtml += "</tr>";
            
        }
    }
 
    $("#tb1-data-id").html(tHtml);
    addTb1EventListner();
    
 }

 function addTb1EventListner(){
    var tblBtns = $("#tb1-data-id").find("button");
    if(tblBtns == null)
          return;
 
    var send_data;
    for(var idx = 0; idx < tblBtns.length; idx++){
       
        tblBtns[idx].addEventListener("click", function() {      
    
            if(this.name == "view") {
                SelAccount = this.value;
                send_data = { account:SelAccount};                
                requestSettingPage(send_data);
            } 
        });
 
    }
 }


 
 function requestAccountPage(){
    var strSearch = $('#search_txt').val();
    var send_data = { search:strSearch};

    var url = getAppUrl("account_count");
    $.ajax({
        type: "POST",
        dataType: "json",
        data: send_data,
        url: url,
        success: function(jResult) {
            // console.log(jResult);
            if(jResult.status == "success")
            {
                TotalCount = jResult.data;
                setFirstPage();
                requestAccount();
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


 function requestAccount(){
    var strSearch = $('#search_txt').val();
    var nPage = getActivePage();
    if(nPage < 0)
        nPage = 1;
    var send_data = { page:nPage, cntper:CountPerPage, search:strSearch};

    var url = getAppUrl("account_list");
    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: send_data,
        success: function(jResult) {
            // console.log(jResult);
            if(jResult.status == "success")
            {
                showAccount(jResult.data);
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



/////////////////////////////////////////////////////////////////////////////////////////////////////

 function setFirstPage_2(){
    
    if(TotalCount2 <= CountPerPage){
        $("#list2-page").hide();
        $("#pagination2-num").html("");
        return;
    } 
        
    var tHtml = "";
    var pageCnt = TotalCount2%CountPerPage==0?TotalCount2/CountPerPage:TotalCount2/CountPerPage + 1;

    $("#page-prev2").hide();

    if(pageCnt > ViewPage){
        pageCnt = ViewPage;
    }

    if(TotalCount2 > CountPerPage * pageCnt) {
        $("#page-next2").show();
    } else $("#page-next2").hide();
        

    for(var page=1; page<=pageCnt; page++){
        if(page==1)
            tHtml += "<button class=\"active\">";
        else tHtml += "<button>";
        
        tHtml += page.toString();
        tHtml += "</button>";
        
    }
    $("#pagination2-num").html(tHtml);
    $("#list2-page").show();
    addPage2EventListner();
    
 }


 function getFirstPage_2(){
    var pageBtns = $("#pagination2-num").find("button");
    if(pageBtns == null)
          return -1;

    if(pageBtns.length < 1)
        return -1;

    if(pageBtns[0].innerHTML.length > 0)
        return parseInt(pageBtns[0].innerHTML);
    return -1;

 } 

 function getActivePage_2(){
    var pageBtns = $("#pagination2-num").find(".active");
    if(pageBtns == null)
          return 1;

    if(pageBtns.length < 1)
        return 1;

    if(pageBtns[0].innerHTML.length > 0)
        return parseInt(pageBtns[0].innerHTML);
    return 1;

 } 

 
 function prevPage_2(){
     
    if(TotalCount2 <= CountPerPage){
        $("#list2-page").hide();
        $("#pagination2-num").html("");
        return;
    }

    var firstPage = getFirstPage_2();
    if(firstPage < 0)
        return;
    
    var layountCnt = parseInt(firstPage/ViewPage) * ViewPage * CountPerPage;

    if(layountCnt > TotalCount2)
        return;
    
    var tHtml = "";
    var pageCnt = layountCnt%CountPerPage==0?layountCnt/CountPerPage:layountCnt/CountPerPage + 1;

    if(layountCnt >  ViewPage * CountPerPage)
        $("#page-prev2").show();
    else $("#page-prev2").hide();

    if(pageCnt > ViewPage){
        pageCnt = ViewPage;
    }
    $("#page-next2").show();

    firstPage -= ViewPage;
    for(var page=1; page<=pageCnt; page++){
        if(page==1)
            tHtml += "<button class=\"active\">";
        else tHtml += "<button>";
        
        tHtml += (firstPage+page-1).toString();
        tHtml += "</button>";
        
    }
    $("#pagination2-num").html(tHtml);
    $("#list2-page").show();
    addPage2EventListner();
    requestSetting();
 }

 function nextPage_2(){

    if(TotalCount2 <= CountPerPage){
        $("#list2-page").hide();
        $("#pagination2-num").html("");
        return;
    }

    var pageBtns = $("#pagination2-num").find("button");
    if(pageBtns == null)
          return;

    if(pageBtns.length < ViewPage)
        return;

    var firstPage = parseInt(pageBtns[0].innerHTML);

    var layountCnt = TotalCount2 - (parseInt(firstPage/ViewPage) + 1) * ViewPage * CountPerPage;

    var tHtml = "";
    var pageCnt = layountCnt%CountPerPage==0?layountCnt/CountPerPage:layountCnt/CountPerPage + 1;

    $("#page-prev2").show();
    if(pageCnt > ViewPage){
        pageCnt = ViewPage;
    }

    if(layountCnt > CountPerPage * pageCnt){
        $("#page-next2").show();
    } else $("#page-next2").hide();

    firstPage += ViewPage ;
    for(var page=1; page<=pageCnt; page++){
        if(page==1)
            tHtml += "<button class=\"active\">";
        else tHtml += "<button>";
        
        tHtml += (firstPage+page-1).toString();
        tHtml += "</button>";
        
        }
    $("#pagination2-num").html(tHtml);
    $("#list2-page").show();
    addPage2EventListner();
    requestSetting();
    
 }

 function addPage2EventListner(){
    var pageBtns = $("#pagination2-num").find("button");
    if(pageBtns == null)
          return;
 
    for(var idx = 0; idx < pageBtns.length; idx++){
       
        pageBtns[idx].addEventListener("click", function() {      
            
            if(this.className != "active"){
                $("#pagination2-num").find(".active").removeClass("active");
                this.className = "active";
                requestSetting();
            }                
            
        });
 
    }
 }


 function showSetting(arrSetting){
    var tHtml = "";
    if(arrSetting != null && arrSetting.length > 0){
       
        var curPage = getActivePage_2();
        var firstIdx = (curPage -1) * CountPerPage;
        for(var idx in arrSetting){
            
            tHtml += "<tr>";
            tHtml += "<td>"+(parseInt(idx)+firstIdx+1).toString()+"</td>";
            tHtml += "<td>"+arrSetting[idx].account+"</td>";
            tHtml += "<td>"+arrSetting[idx].name+"</td>";
            tHtml += "<td>"+arrSetting[idx].modified+"</td>";
            tHtml += "<td><button name=\"delete\" value=\"" + arrSetting[idx].name + "\">삭제</button></td>";
            tHtml += "<td><button name=\"download\" value=\"" + arrSetting[idx].name + "\">다운로드</button></td>";
            tHtml += "</tr>";
            
        }
    }
 
    $("#tb2-data-id").html(tHtml);
    addTb2EventListner();
    
 }

 function addTb2EventListner(){
    var tblBtns = $("#tb2-data-id").find("button");
    if(tblBtns == null)
          return;
 
    var sendData;
    for(var idx = 0; idx < tblBtns.length; idx++){
       
        tblBtns[idx].addEventListener("click", function() {      
    
            if(this.name == "delete") {
                sendData = { account:SelAccount, file:this.value};
                requestDeleteSetting(sendData);
            } else if(this.name == "download") {
                sendData = { account:SelAccount, file:this.value};
                requestDownSetting(sendData);
            } 
        });
 
    }
 }


 
 function requestSettingPage(send_data){
    var url = getAppUrl("setting_count");

    $.ajax({
        type: "POST",
        dataType: "json",
        data: send_data,
        url: url,
        success: function(jResult) {
            // console.log(jResult);
            if(jResult.status == "success")
            {
                TotalCount2 = jResult.data;
                setFirstPage_2();
                requestSetting();
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


 function requestSetting(){
    
    var nPage = getActivePage_2();
    if(nPage < 0)
        nPage = 1;
    var send_data = { page:nPage, cntper:CountPerPage, account:SelAccount};

    var url = getAppUrl("setting_list");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: send_data,
        success: function(jResult) {
            // console.log(jResult);
            if(jResult.status == "success")
            {
                showSetting(jResult.data);
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


 function requestDeleteSetting(send_data){
    if(!confirm("삭제하시겠습니까?"))
        return;
    var url = getAppUrl("setting_delete");

    $.ajax({
        type: "POST",
        dataType: "json",
        data: send_data,
        url: url,
        success: function(jResult) {
            // console.log(jResult);
            if(jResult.status == "success")
            {
                requestSetting();
            } else if(jResult.status == "fail")
            {    
                if(jResult.code == 2)
                    alert('파일삭제가 거절되었습니다.');       
                else
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


 
 function requestDownSetting(send_data){
    
    location.href = getAppUrl("setting_download/?account="+send_data.account+"&file="+send_data.file);

 }

