
 var TotalCount = 0;
 var CountPerPage = 20;
 var ViewPage = 10;

$(document).ready(function(){
    
    var elMainHeader = $(".main-header");
    var elMainSidebar = $(".main-sidebar");
    var elMainContainer = $(".main-container");

    if(elMainHeader.length < 1 || elMainSidebar.length < 1)
        return;

    var height = elMainHeader.height() + 10;
    var sHeight = height.toString() + "px";
    
    elMainSidebar.css("top", sHeight);
    elMainContainer.css("margin-top", sHeight);

    window.onresize = function(event) {
        height = elMainHeader.height() + 10;
        sHeight = height.toString() + "px";
        
        elMainSidebar.css("top", sHeight);
        elMainContainer.css("margin-top", sHeight);
  
    }

 });


 function getAppUrl(endSegment){
     return "/"+$("#category_name").text()+"/"+endSegment;
 }


 
 function setFirstPage(){
    
    if(TotalCount <= CountPerPage){
        $("#list-page").hide();
        $("#pagination-num").html("");
        return;
    } 
        
    var tHtml = "";
    var pageCnt = TotalCount%CountPerPage==0?TotalCount/CountPerPage:TotalCount/CountPerPage + 1;

    $("#page-prev").hide();

    if(pageCnt > ViewPage){
        pageCnt = ViewPage;
    }
    
    if(TotalCount > CountPerPage * pageCnt)
        $("#page-next").show();
    else $("#page-next").hide();
        

    for(var page=1; page<=pageCnt; page++){
        if(page==1)
            tHtml += "<button class=\"active\">";
        else tHtml += "<button>";
        
        tHtml += page.toString();
        tHtml += "</button>";
        
    }
    $("#pagination-num").html(tHtml);
    $("#list-page").show();
    addPageEventListner();
     
    
 }

 function getFirstPage(){
    var pageBtns = $("#pagination-num").find("button");
    if(pageBtns == null)
          return -1;

    if(pageBtns.length < 1)
        return -1;

    if(pageBtns[0].innerHTML.length > 0)
        return parseInt(pageBtns[0].innerHTML);
    return -1;

 } 

 function getActivePage(){
    var pageBtns = $("#pagination-num").find(".active");
    if(pageBtns == null)
          return 1;

    if(pageBtns.length < 1)
        return 1;

    if(pageBtns[0].innerHTML.length > 0)
        return parseInt(pageBtns[0].innerHTML);
    return 1;

 } 

 function prevPage(){
     
    if(TotalCount <= CountPerPage){
        $("#list-page").hide();
        $("#pagination-num").html("");
        return;
    }

    var firstPage = getFirstPage();
    if(firstPage < 0)
        return;
    
    var layountCnt = parseInt(firstPage/ViewPage) * ViewPage * CountPerPage;

    if(layountCnt > TotalCount)
        return;
    
    var tHtml = "";
    var pageCnt = layountCnt%CountPerPage==0?layountCnt/CountPerPage:layountCnt/CountPerPage + 1;

    if(layountCnt >  ViewPage * CountPerPage)
        $("#page-prev").show();
    else $("#page-prev").hide();

    if(pageCnt > ViewPage){
        pageCnt = ViewPage;
    }
    $("#page-next").show();

    firstPage -= ViewPage;
    for(var page=1; page<=pageCnt; page++){
        if(page==1)
            tHtml += "<button class=\"active\">";
        else tHtml += "<button>";
        
        tHtml += (firstPage+page-1).toString();
        tHtml += "</button>";
        
    }
    $("#pagination-num").html(tHtml);
    $("#list-page").show();
    addPageEventListner();
    requestList();
 }

 function nextPage(){

    if(TotalCount <= CountPerPage){
        $("#list-page").hide();
        $("#pagination-num").html("");
        return;
    }

    var pageBtns = $("#pagination-num").find("button");
    if(pageBtns == null)
          return;

    if(pageBtns.length < ViewPage)
        return;

    var firstPage = parseInt(pageBtns[0].innerHTML);

    var layountCnt = TotalCount - (parseInt(firstPage/ViewPage) + 1) * ViewPage * CountPerPage;

    var tHtml = "";
    var pageCnt = layountCnt%CountPerPage==0?layountCnt/CountPerPage:layountCnt/CountPerPage + 1;

    $("#page-prev").show();
    if(pageCnt > ViewPage){
        pageCnt = ViewPage;
    }
    
    if(layountCnt > CountPerPage * pageCnt)
        $("#page-next").show();
    else $("#page-next").hide();

    firstPage += ViewPage ;
    for(var page=1; page<=pageCnt; page++){
        if(page==1)
            tHtml += "<button class=\"active\">";
        else tHtml += "<button>";
        
        tHtml += (firstPage+page-1).toString();
        tHtml += "</button>";
        
        }
    $("#pagination-num").html(tHtml);
    $("#list-page").show();
    addPageEventListner();
    requestList();
    
 }

 function addPageEventListner(){
    var pageBtns = $("#pagination-num").find("button");
    if(pageBtns == null)
          return;
 
    for(var idx = 0; idx < pageBtns.length; idx++){
       
        pageBtns[idx].addEventListener("click", function() {      
            
            if(this.className != "active"){
                $("#pagination-num").find(".active").removeClass("active");
                this.className = "active";
                requestList();
            }
            
        });
 
    }
 }
