var m_accounts = null;

function showIdAndPwd(arrData) {
    var tHtml = "";
    if (arrData != null && arrData.length > 0) {

        for (var idx in arrData) {
            tHtml += "<tr>";
            tHtml += "<td>" + arrData[idx].id + "</td>";
            tHtml += "<td>" + arrData[idx].pwd + "</td>";
            tHtml += "</tr>";
        }
        $("#tb-id").show();
    } else $("#tb-id").hide();

    $("#tb-data-id").html(tHtml);
}

function autoCreate() {

    var create_count = $("#mb_count").val();
    if (create_count < 1) {
        alert("생성개수를 정확히 입력해주십시오.");
        return;
    }

    var url = getAppUrl("member_createids");

    var send_data = { create_count: create_count };

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: send_data,
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                m_accounts = jResult.data;
                showIdAndPwd(jResult.data);
            } else if (jResult.status == "logout") {
                location.replace('/');
            } else if (jResult.status == "fail") {
                alert("잘못된 계정정보입니다.");
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}


function saveMember() {

    var objMember = new Object();
    objMember.mb_name = $("#mb_name").val();
    objMember.mb_emp_fid = $("#mb_emp option:selected").val();
    objMember.mb_time_limit = $("#mb_time_limit").val();
    objMember.mb_vip = $("#mb_vip option:selected").val();
    objMember.mb_accounts = m_accounts;

    if (objMember.mb_emp_fid == undefined ||
        objMember.mb_accounts == null || objMember.mb_accounts.length < 1) {
        alert("회원정보를 정확히 입력해주십시오.");
        return;
    }

    var jsonData = JSON.stringify(objMember);
    //console.log(jsonData);

    if (!confirm("저장하시겠습니까?"))
        return;

    var url = getAppUrl("member_create");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                location.replace(getAppUrl("member"));
            } else if (jResult.status == "logout") {
                location.replace('/');
            } else if (jResult.status == "fail") {
                if (jResult.code == 5)
                    alert("중복된 아이디입니다.");
                else if (jResult.code == 6)
                    alert("중복된 닉네임입니다.");
                else alert("등록이 실패되었습니다.");
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}

function cancelMember() {

    location.href = getAppUrl("member");
}