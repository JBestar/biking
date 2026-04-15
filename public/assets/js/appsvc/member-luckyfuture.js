

 function showMember(arrMember){
    var tHtml = "";
    if(arrMember != null && arrMember.length > 0){
        
        var curPage = getActivePage();
        var firstIdx = (curPage -1) * CountPerPage;
        for(var idx in arrMember){
            
            if(arrMember[idx].mb_emp_color == undefined || arrMember[idx].mb_emp_color.length < 1){
                tHtml += "<tr>";
            } else {
                tHtml += "<tr bgcolor=\""+arrMember[idx].mb_emp_color+"\">";                
            }
            tHtml += "<td>"+(parseInt(idx)+firstIdx+1).toString()+"</td>";
            if(arrMember[idx].mb_emp_name != undefined)
                tHtml += "<td>"+arrMember[idx].mb_emp_name+"</td>";
            else tHtml += "<td></td>";
            tHtml += "<td>"+arrMember[idx].mb_uid+" / "+arrMember[idx].mb_pwd+"</td>";
            tHtml += "<td>"+arrMember[idx].mb_nickname+"</td>";
            if(arrMember[idx].mb_time_join.length > 10)
                tHtml += "<td>"+arrMember[idx].mb_time_join.substr(0, 10)+"</td>";
            else tHtml += "<td></td>";
    
            if(arrMember[idx].mb_time_last.length > 10)
                tHtml += "<td>"+arrMember[idx].mb_time_last.substr(0, 10)+"</td>";
            else tHtml += "<td></td>";
    
            if(arrMember[idx].mb_time_limit.length > 10)
                tHtml += "<td>"+arrMember[idx].mb_time_limit.substr(0, 10)+"</td>";
            else tHtml += "<td></td>";

            if(parseInt(arrMember[idx].mb_state_active) == 1)
                tHtml += "<td><button name=\"permit\" value=\"" + arrMember[idx].mb_fid + "\" class=\"permit\">승인</button></td>";
            else 
                tHtml += "<td><button name=\"stop\" value=\"" + arrMember[idx].mb_fid + "\" class=\"stop\">차단</button></td>";
    
            tHtml += "<td><button name=\"edit\" value=\"" + arrMember[idx].mb_fid + "\">수정</button>";
            tHtml += "<button name=\"delete\" value=\"" + arrMember[idx].mb_fid + "\" >삭제</button></td>";
    
            tHtml += "<td><button name=\"1week\" value=\"" + arrMember[idx].mb_fid + "\">1주연장</button>";
            tHtml += "<button name=\"2week\" value=\"" + arrMember[idx].mb_fid + "\">2주연장</button>";
            tHtml += "<button name=\"1month\" value=\"" + arrMember[idx].mb_fid + "\" >1달연장</button></td>";
            
            if(arrMember[idx].mb_prop_1 == undefined){
                tHtml += "<td></td> <td></td>"
            } else{
                tHtml += "<td>"+arrMember[idx].mb_prop_1+"</td>";
                tHtml += "<td><button name=\"order_inc\" order_cnt=\""+arrMember[idx].mb_prop_1+"\" value=\"" + arrMember[idx].mb_fid + "\"> + </button>";
                tHtml += "<button name=\"order_dec\" order_cnt=\""+arrMember[idx].mb_prop_1+"\" value=\"" + arrMember[idx].mb_fid + "\"> - </button></td>";
            }
            

            
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
    
            if(this.name == "permit"){
                jsonData = { "mb_fid":this.value, "mb_state_active":0};
                requestUpdateMember(jsonData);
            } else if(this.name == "stop"){
                jsonData = { "mb_fid":this.value, "mb_state_active":1};
                requestUpdateMember(jsonData);
            } else if(this.name == "edit"){
                location.href = getAppUrl("member_edit/"+this.value);
            } else if(this.name == "delete"){
                jsonData = { "mb_fid":this.value};
                requestDeleteMember(jsonData);
            } else if(this.name == "1week"){
                jsonData = { "mb_fid":this.value, "mb_time_limit":"1"};
                requestUpdateMember(jsonData);
            } else if(this.name == "2week"){
                jsonData = { "mb_fid":this.value, "mb_time_limit":"2"};
                requestUpdateMember(jsonData);
            } else if(this.name == "1month"){
                jsonData = { "mb_fid":this.value, "mb_time_limit":"3"};
                requestUpdateMember(jsonData);
            } else if(this.name == "order_inc"){
                orderCnt = parseInt($(this).attr('order_cnt'));
                if(orderCnt < 10){
                    orderCnt ++;
                    jsonData = { "mb_fid":this.value, "mb_prop_1":orderCnt};
                    requestUpdateMember(jsonData);
                }   
            } else if(this.name == "order_dec"){
                orderCnt = parseInt($(this).attr('order_cnt'));
                if(orderCnt > 0){
                    orderCnt --;
                    jsonData = { "mb_fid":this.value, "mb_prop_1":orderCnt};
                    requestUpdateMember(jsonData);
                }
            }
        });
 
    }
 }

