

 function showConnector(arrSession){
    var tHtml = "";
    if(arrSession != null && arrSession.length > 0){
       
        var curPage = getActivePage();
        var firstIdx = (curPage -1) * CountPerPage;
        var money_begin = 0, money_current = 0;
        for(var idx in arrSession){

            if(arrSession[idx].mb_emp_color == undefined || arrSession[idx].mb_emp_color.length < 1){
                tHtml += "<tr>";
            } else {
                tHtml += "<tr bgcolor=\""+arrSession[idx].mb_emp_color+"\">";                
            }
            tHtml += "<td>"+(parseInt(idx)+firstIdx+1).toString()+"</td>";
            tHtml += "<td>"+arrSession[idx].sess_mb_uid;
            if(parseInt(arrSession[idx].mb_vip) > 0)
                tHtml +="<br>(VIP)";
            tHtml +="</td>";

            if(parseInt(arrSession[idx].sess_running) == 1){
                tHtml +="<td>";
                if(arrSession[idx].sess_betting_domain.length > 0)
                    tHtml += arrSession[idx].sess_betting_domain + "<br>";
                tHtml +=  arrSession[idx].sess_betting_user;
                if(parseInt(arrSession[idx].sess_betting_real)==1){
                    tHtml += "<br>자동";
                }
                else {
                    tHtml += "<br>수동";
                }
                tHtml +="</td>"; 
                money_begin = parseInt(arrSession[idx].sess_money_begin);
                money_current = parseInt(arrSession[idx].sess_money_current);
                tHtml += (money_current + 100000) < money_begin ? "<td class=\"red left\">" : "<td class=\"left\">" ;
                tHtml += "시작:" + money_begin.toLocaleString()+"원";
                tHtml += "<br>현재:" + money_current.toLocaleString()+"원";
                if(money_current >= money_begin)
                    tHtml += "<br>수익:" + (money_current-money_begin).toLocaleString()+"원";
                else tHtml += "<br>손실:" + (money_begin-money_current).toLocaleString()+"원";
                tHtml +="</td><td></td>";
                
                tHtml +="<td>";
                tHtml +="</td><td>";
                tHtml +="</td><td>";
                tHtml +="</td><td>";
                tHtml +="</td>";
                
            } else tHtml +="<td></td><td></td><td></td><td></td><td></td><td></td><td></td>";
            

            if(arrSession[idx].sess_time_begin.length > 18)
                tHtml += "<td>시작:"+arrSession[idx].sess_time_begin.substr(5, 11);
            else tHtml += "<td>";
    
            if(arrSession[idx].sess_time_last.length > 18)
                tHtml += "<br>업뎃:"+arrSession[idx].sess_time_last.substr(5, 11)+"</td>";
            else tHtml += "</td>";
            tHtml += "<td>"+arrSession[idx].sess_app_title+"<br>"+arrSession[idx].sess_app_version+"</td>";
            tHtml += "<td>"+arrSession[idx].sess_pub_addr+"</td>";
            tHtml += "</tr>";
        }
    }
 
    $("#tb-data-id").html(tHtml);
    
    
 }