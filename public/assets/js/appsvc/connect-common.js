$(document).ready(function(){
    
    requestPage();
    loop();
 });


 function loop() {

    requestConnector(); 

    setTimeout( function() { loop(); }, 60000 );

}


 function findConnector(){
     requestPage();
 }
 

 function requestList(){
    requestConnector();
}

 

 function requestPage(){
    var strSearch = $('#search_txt').val();
    var strEmp = $("#stf_emp option:selected").val();
    if(strEmp == undefined)
        return;
    var send_data = { stf_emp:strEmp, search:strSearch};

    var url = getAppUrl("connector_count");
    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: send_data,
        success: function(jResult) {
            // console.log(jResult);
            if(jResult.status == "success")
            {
                TotalCount = jResult.data;
                setFirstPage();
                requestConnector();
            } else if(jResult.status == "fail")
            {    
                alert('잘못된 계정정보입니다.');       
                    
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


 function requestConnector(){
    var strSearch = $('#search_txt').val();
    var strEmp = $("#stf_emp option:selected").val();
    if(strEmp == undefined)
        return;
    var nPage = getActivePage();
    if(nPage < 0)
        nPage = 1;
    var send_data = { stf_emp:strEmp, search:strSearch, page:nPage, cntper:CountPerPage};

    var url = getAppUrl("connector_list");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: send_data,
        success: function(jResult) {
            // console.log(jResult);
            if(jResult.status == "success")
            {
                showConnector(jResult.data, jResult.field);
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

function getTdHtml(field, objSess) {
    tHtml = "";
    if(field == "user_info"){
        tHtml += "<td>"+objSess.sess_mb_uid;
        if(parseInt(objSess.mb_vip) > 0)
            tHtml +="<br>(VIP)";
        tHtml +="</td>";
    } else if(field == "guser_info"){
        tHtml +="<td>";
        if(parseInt(objSess.sess_running) == 1){

            if(objSess.sess_betting_domain.length > 0)
                tHtml += objSess.sess_betting_domain + "<br>";
            tHtml +=  objSess.sess_betting_user + "<br>";
            tHtml += parseInt(objSess.sess_betting_real)==1?"실제베팅":"가상베팅";
        } 
        tHtml +="</td>"; 
    } else if(field == "real_money"){
        if(parseInt(objSess.sess_running) == 1 && parseInt(objSess.sess_betting_real)==1){
            money_begin = parseInt(objSess.sess_money_begin);
            money_current = parseInt(objSess.sess_money_current);
            tHtml += (money_current + 100000) < money_begin ? "<td class=\"red left\">" : "<td class=\"left\">" ;
            tHtml += "시작:" + money_begin.toLocaleString()+"원";
            tHtml += "<br>현재:" + money_current.toLocaleString()+"원";
            if(money_current >= money_begin)
                tHtml += "<br>수익:" + (money_current-money_begin).toLocaleString()+"원";
            else tHtml += "<br>손실:" + (money_begin-money_current).toLocaleString()+"원";
            
        } else tHtml +="<td>";
        tHtml +="</td>";
    } else if(field == "virt_money"){
        if(parseInt(objSess.sess_running) == 1 && parseInt(objSess.sess_betting_real)!=1){
            money_begin = parseInt(objSess.sess_virtual_begin);
            money_current = parseInt(objSess.sess_virtual_current);
            tHtml += (money_current + 100000) < money_begin ? "<td class=\"red left\">" : "<td class=\"left\">" ;
            tHtml += "시작:" + money_begin.toLocaleString()+"원";
            tHtml += "<br>현재:" + money_current.toLocaleString()+"원";
            if(money_current >= money_begin)
                tHtml += "<br>수익:" + (money_current-money_begin).toLocaleString()+"원";
            else tHtml += "<br>손실:" + (money_begin-money_current).toLocaleString()+"원";
            
        } else tHtml +="<td>";
        tHtml +="</td>";
    } else if(field == "room_0"){
        tHtml +="<td>";
        if(parseInt(objSess.sess_running) == 1){
            tHtml += (parseInt(objSess.sess_room_0_level)+1) + "레벨";
            tHtml += "<br>( "+ objSess.sess_room_0_win + "승 : " + objSess.sess_room_0_loss + "패 )";
        } 
        tHtml +="</td>";
    } else if(field == "room_1"){
        tHtml +="<td>";
        if(parseInt(objSess.sess_running) == 1){
            tHtml += (parseInt(objSess.sess_room_1_level)+1) + "레벨";
            tHtml += "<br>( "+ objSess.sess_room_1_win + "승 : " + objSess.sess_room_1_loss + "패 )";
        } 
        tHtml +="</td>";
    } else if(field == "room_2"){
        tHtml +="<td>";
        if(parseInt(objSess.sess_running) == 1){
            tHtml += (parseInt(objSess.sess_room_2_level)+1) + "레벨";
            tHtml += "<br>( "+ objSess.sess_room_2_win + "승 : " + objSess.sess_room_2_loss + "패 )";
        } 
        tHtml +="</td>";
    } else if(field == "room_3"){
        tHtml +="<td>";
        if(parseInt(objSess.sess_running) == 1){
            tHtml += (parseInt(objSess.sess_room_3_level)+1) + "레벨";
            tHtml += "<br>( "+ objSess.sess_room_3_win + "승 : " + objSess.sess_room_3_loss + "패 )";
        } 
        tHtml +="</td>";
    } else if(field == "memo_1"){
        tHtml +="<td>";
        tHtml += objSess.sess_memo_1;
        tHtml +="</td>";
    } else if(field == "memo_2"){
        tHtml +="<td>";
        tHtml += objSess.sess_memo_2;
        tHtml +="</td>";
    } else if(field == "prop_1"){
        tHtml +="<td>";
        tHtml += objSess.sess_prop_1.toLocaleString();
        tHtml +="</td>";
    } else if(field == "prop_2"){
        tHtml +="<td>";
        tHtml += objSess.sess_prop_2.toLocaleString();
        tHtml +="</td>";
    } else if(field == "sess_time"){
        tHtml +="<td>";
        if(objSess.sess_time_begin.length > 18)
            tHtml += "시작:"+objSess.sess_time_begin.substr(5, 11);
        if(objSess.sess_time_last.length > 18)
            tHtml += "<br>업뎃:"+objSess.sess_time_last.substr(5, 11);
        tHtml += "</td>";

    } else if(field == "app_name"){
        tHtml += "<td>";
        tHtml += objSess.sess_app_title+"<br>"+objSess.sess_app_version;
        tHtml += "</td>";
    } else if(field == "ip_addr"){
        tHtml += "<td>";
        tHtml += objSess.sess_pub_addr;
        tHtml += "</td>";
    }
    return tHtml;
}