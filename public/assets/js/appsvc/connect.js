

 function showConnector(arrSession, arrField, act=0){
    var tHtml = "";
    if(arrSession != null && arrSession.length > 0 && arrField != null){
       
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
            for(var idy in arrField){
                tHtml += getTdHtml(arrField[idy], arrSession[idx], act);
            }
            
            tHtml += "</tr>";
        }
    }
 
    $("#tb-data-id").html(tHtml);
    
    
 }