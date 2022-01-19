$(document).ready(function() {

    requestDesign();
});

function showDesign(arrField) {
    var tHtml = "";
    if (arrField != null && arrField.length > 0) {
        var firstIdx = 0;

        for (var idx in arrField) {
            tHtml += "<tr>";
            tHtml += "<td>" + (parseInt(idx) + firstIdx + 1).toString() + "</td>";
            tHtml += getTdHtml(arrField[idx]);
            tHtml += "<td><button class=\"shape\" name=\"move_up\" value=\"" + idx + "\"><i class=\"fas fa-arrow-alt-circle-up\"></i></button>";
            tHtml += "<button class=\"shape\" name=\"move_down\" value=\"" + idx + "\"><i class=\"fas fa-arrow-alt-circle-down\"></i></button></td>";
            tHtml += "</tr>";
        }


    }

    $("#tb-data-id").html(tHtml);
    addTbEventListner();
}


function getTdHtml(objField) {

    var field = objField.key;
    tHtml = "";
    tHtml += "<td>";
    tHtml += "<input type=\"text\" name=\"" + field + "\" value=\"" + objField.value + "\">";
    tHtml += "</td>";
    if (field == "user_info") {
        tHtml += "<td>구조체</td>";
        tHtml += "<td>오토정보</td>";
    } else if (field == "guser_info") {
        tHtml += "<td>구조체</td>";
        tHtml += "<td>게임유저정보(도메인, 유저명, 베팅상태)</td>";
    } else if (field == "real_money") {
        tHtml += "<td>구조체</td>";
        tHtml += "<td>실제금액상태(시작, 현재, 손실금액)</td>";
    } else if (field == "virt_money") {
        tHtml += "<td>구조체</td>";
        tHtml += "<td>가상금액상태(시작, 현재, 손실금액)</td>";
    } else if (field == "room_0") {
        tHtml += "<td>구조체</td>";
        tHtml += "<td>1번방(레벨, 승수:패수)</td>";
    } else if (field == "room_1") {
        tHtml += "<td>구조체</td>";
        tHtml += "<td>2번방(레벨, 승수:패수)</td>";
    } else if (field == "room_2") {
        tHtml += "<td>구조체</td>";
        tHtml += "<td>3번방(레벨, 승수:패수)</td>";
    } else if (field == "room_3") {
        tHtml += "<td>구조체</td>";
        tHtml += "<td>4번방(레벨, 승수:패수)</td>";
    } else if (field == "memo_1") {
        tHtml += "<td>문자열</td>";
        tHtml += "<td>memo_1</td>";
    } else if (field == "memo_2") {
        tHtml += "<td>문자열</td>";
        tHtml += "<td>memo_2</td>";
    } else if (field == "memo_3") {
        tHtml += "<td>문자열</td>";
        tHtml += "<td>memo_3</td>";
    } else if (field == "memo_4") {
        tHtml += "<td>문자열</td>";
        tHtml += "<td>memo_4</td>";
    } else if (field == "memo_5") {
        tHtml += "<td>문자열</td>";
        tHtml += "<td>memo_5</td>";
    } else if (field == "prop_1") {
        tHtml += "<td>숫자</td>";
        tHtml += "<td>prop_1</td>";
    } else if (field == "prop_2") {
        tHtml += "<td>숫자</td>";
        tHtml += "<td>prop_2</td>";
    } else if (field == "prop_3") {
        tHtml += "<td>숫자</td>";
        tHtml += "<td>prop_3</td>";
    } else if (field == "prop_4") {
        tHtml += "<td>숫자</td>";
        tHtml += "<td>prop_4</td>";
    } else if (field == "prop_5") {
        tHtml += "<td>숫자</td>";
        tHtml += "<td>prop_5</td>";
    } else if (field == "sess_time") {
        tHtml += "<td>구조체</td>";
        tHtml += "<td>세션시간(시작, 마감시간)</td>";
    } else if (field == "app_name") {
        tHtml += "<td>구조체</td>";
        tHtml += "<td>제품정보(제품명, 버젼정보)</td>";
    } else if (field == "ip_addr") {
        tHtml += "<td>구조체</td>";
        tHtml += "<td>아이피주소</td>";
    } else tHtml += "<td></td><td></td>";

    tHtml += "<td><select>";
    tHtml += "<option value=\"0\" ";
    tHtml += parseInt(objField.hidden) == 0 ? "selected" : "";
    tHtml += ">보이기</option>";
    tHtml += "<option value=\"1\"";
    tHtml += parseInt(objField.hidden) == 1 ? "selected" : "";
    tHtml += ">감추기</option>";
    tHtml += "</select></td>";
    return tHtml;
}


function addTbEventListner() {
    var tblBtns = $("#tb-data-id").find("button");
    if (tblBtns == null)
        return;

    for (var idx = 0; idx < tblBtns.length; idx++) {

        tblBtns[idx].addEventListener("click", function() {

            if (this.name == "move_up") {
                moveUp(this);
            } else if (this.name == "move_down") {
                moveDown(this);
            }
        });

    }
}

function moveUp(el) {
    var tr = $(el).parent().parent(); // 클릭한 버튼이 속한 tr 요소
    tr.prev().before(tr); // 현재 tr 의 이전 tr 앞에 선택한 tr 넣기
    var trOrder = parseInt(tr.children().eq(0).text());
    if (trOrder > 1) {
        tr.children().eq(0).text(trOrder - 1);
        tr.next().children().eq(0).text(trOrder);
    }
}

function moveDown(el) {
    var tr = $(el).parent().parent(); // 클릭한 버튼이 속한 tr 요소
    tr.next().after(tr); // 현재 tr 의 다음 tr 뒤에 선택한 tr 넣기
    var trOrder = parseInt(tr.children().eq(0).text());
    if (trOrder > 0 && trOrder < $("#tb-data-id tr").length) {
        tr.children().eq(0).text(trOrder + 1);
        tr.prev().children().eq(0).text(trOrder);
    }
}





function requestDesign() {
    var sendData = { cat_id: $("#category_id").text() };
    $.ajax({
        type: "POST",
        dataType: "json",
        data: sendData,
        url: "/staff/design_connect_get",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showDesign(jResult.data);
            } else if (jResult.status == "fail") {
                alert('잘못된 계정정보입니다.');

            } else if (jResult.status == "logout") {
                location.replace('/');
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });
}



function saveDesign() {

    var arrField = new Array();
    var arrTr = $("#tb-data-id tr");
    $.each(arrTr, function(idx, tr) {
        var objFd = new Object();

        objFd.order = $(tr).children().eq(0).text();
        objFd.key = $(tr).children().eq(1).find('input').attr("name");
        objFd.value = $(tr).children().eq(1).find('input').val();
        objFd.hidden = $(tr).children().eq(4).find('select').val();

        arrField.push(objFd);

    });

    if (!confirm("저장하시겠습니까?"))
        return;

    var jsonData = JSON.stringify(arrField);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/staff/design_connect_modify",
        data: { json_: jsonData, cat_id: $("#category_id").text() },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                requestDesign();
                alert("저장되었습니다.");
            } else if (jResult.status == "logout") {
                location.replace('/');
            } else if (jResult.status == "fail") {
                alert("저장이 실패되었습니다.");
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });

}



function gotoAdmin() {
    location.href = "/staff/admin";

}