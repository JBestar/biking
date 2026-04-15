

 function showHistory(arrBet){
    var tHtml = "", tSumHtml = "";
    if(arrBet != null && arrBet.length > 0){
       
        var curPage = getActivePage();
        var firstIdx = (curPage -1) * CountPerPage;
        var total_wins = 0, total_loss = 0, total_earn = 0, total_earn_sum = 0;
        for(var idx in arrBet){

            if(arrBet[idx].mb_emp_color == undefined || arrBet[idx].mb_emp_color.length < 1){
                tHtml += "<tr>";
            } else {
                tHtml += "<tr bgcolor=\""+arrBet[idx].mb_emp_color+"\">";                
            }
            tHtml += "<td>"+(parseInt(idx)+firstIdx+1).toString()+"</td>";
            tHtml += "<td>"+arrBet[idx].bet_mb_uid+"</td>";
            tHtml += "<td>"+arrBet[idx].bet_guser+"</td>";
            tHtml += "<td>"+arrBet[idx].bet_room1_wins+"승 : "+arrBet[idx].bet_room1_loss+"패</td>";
            tHtml += "<td>"+arrBet[idx].bet_room2_wins+"승 : "+arrBet[idx].bet_room2_loss+"패</td>";
            tHtml += "<td>"+arrBet[idx].bet_room3_wins+"승 : "+arrBet[idx].bet_room3_loss+"패</td>";
            tHtml += "<td>"+arrBet[idx].bet_room4_wins+"승 : "+arrBet[idx].bet_room4_loss+"패</td>";
            total_wins = parseInt(arrBet[idx].bet_room1_wins) + parseInt(arrBet[idx].bet_room2_wins);
            total_wins+= parseInt(arrBet[idx].bet_room3_wins) + parseInt(arrBet[idx].bet_room4_wins);
            total_loss = parseInt(arrBet[idx].bet_room1_loss) + parseInt(arrBet[idx].bet_room2_loss);
            total_loss+= parseInt(arrBet[idx].bet_room3_loss) + parseInt(arrBet[idx].bet_room4_loss);
            tHtml += "<td>"+total_wins+"승 : "+total_loss+"패</td>";


            if(parseInt(arrBet[idx].bet_room1_earn) < -100000)
                tHtml += "<td class=\"red right\">";
            else if(parseInt(arrBet[idx].bet_room1_earn) < 0)
                tHtml += "<td class=\"orange right\">";
            else tHtml += "<td class=\"right\">";     
            tHtml += parseInt(arrBet[idx].bet_room1_earn).toLocaleString()+"원</td>";

            if(parseInt(arrBet[idx].bet_room2_earn) < -100000)
                tHtml += "<td class=\"red right\">";
            else if(parseInt(arrBet[idx].bet_room2_earn) < 0)
                tHtml += "<td class=\"orange right\">";
            else tHtml += "<td class=\"right\">";     
            tHtml += parseInt(arrBet[idx].bet_room2_earn).toLocaleString()+"원</td>";
            
            if(parseInt(arrBet[idx].bet_room3_earn) < -100000)
                tHtml += "<td class=\"red right\">";
            else if(parseInt(arrBet[idx].bet_room3_earn) < 0)
                tHtml += "<td class=\"orange right\">";
            else tHtml += "<td class=\"right\">";     
            tHtml += parseInt(arrBet[idx].bet_room3_earn).toLocaleString()+"원</td>";
            
            if(parseInt(arrBet[idx].bet_room4_earn) < -100000)
                tHtml += "<td class=\"red right\">";
            else if(parseInt(arrBet[idx].bet_room4_earn) < 0)
                tHtml += "<td class=\"orange right\">";
            else tHtml += "<td class=\"right\">";     
            tHtml += parseInt(arrBet[idx].bet_room4_earn).toLocaleString()+"원</td>";
            
            total_earn = parseInt(arrBet[idx].bet_room1_earn) + parseInt(arrBet[idx].bet_room2_earn);
            total_earn+= parseInt(arrBet[idx].bet_room3_earn) + parseInt(arrBet[idx].bet_room4_earn);
            total_earn_sum += total_earn;

            if(total_earn < 0)
                tHtml += "<td class=\"red right\">";
            else tHtml += "<td class=\"right\">";     
            tHtml += total_earn.toLocaleString()+"원</td>";
            tHtml += "</tr>";
        }

        if(total_earn_sum >= 0){
            tSumHtml = "수익: "+ total_earn_sum.toLocaleString() +"원";
            $("#total_earn_sum").css('color', 'blue');
        } else {
            tSumHtml = "손실: "+ total_earn_sum.toLocaleString() +"원";
            $("#total_earn_sum").css('color', 'red');
        }
    }
 
    $("#tb-data-id").html(tHtml);
    $("#total_earn_sum").html(tSumHtml);
    
 }

 