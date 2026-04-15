var m_accounts = null;

function showIdAndName(arrData) {
    var tHtml = "";
    if (arrData != null && arrData.length > 0) {

        for (var idx in arrData) {
            tHtml += "<tr>";
            tHtml += "<td>" + arrData[idx].id + "</td>";
            tHtml += "<td>" + arrData[idx].name + "</td>";
            tHtml += "<td>" + arrData[idx].pwd + "</td>";
            tHtml += "</tr>";
        }
        $("#tb-id").show();
    } else $("#tb-id").hide();

    $("#tb-data-id").html(tHtml);
}


function changePwdType() {
    if ($('#mb_pwd_type').val() == 0) {
        $('#mb_pwd_str').show();
        $('#mb_pwd_digit').hide();
    } else {
        $('#mb_pwd_str').hide();
        $('#mb_pwd_digit').show();
    }
}


function autoCreate() {

    var create_count = $('#mb_count').val().length > 0 ? parseInt($('#mb_count').val()) : 0;
    if (create_count < 1) {
        alert("생성개수를 정확히 입력해주십시오.");
        return;
    }

    if (create_count >= 100) {
        alert("생성개수를 초과하셧습니다.");
        return;
    }


    var arrMember = new Array();
    let objMember = null;

    let uid_pre = $('#mb_uid_pre').val();
    let uid_num = $('#mb_uid_num').val().length > 0 ? parseInt($('#mb_uid_num').val()) : 0;
    let uid_suf = $('#mb_uid_suf').val();

    let name_pre = $('#mb_name_pre').val();
    let name_num = $('#mb_name_num').val().length > 0 ? parseInt($('#mb_name_num').val()) : 0;
    let name_suf = $('#mb_name_suf').val();

    let pwd_type = $('#mb_pwd_type').val();

    let pwd_str = '';
    let pwd_digit = 0;
    if (pwd_type == 0) {
        pwd_str = $('#mb_pwd_str').val();

        if (pwd_str.length < 3 || pwd_str.length > 10) {
            alert("비번길이는 3~10자리입니다.");
            return;
        }

    } else {
        pwd_digit = $('#mb_pwd_digit').val().length > 0 ? parseInt($('#mb_pwd_digit').val()) : 0;

        if (pwd_digit < 3 || pwd_digit > 10) {
            alert("비번길이는 3~10자리입니다.");
            return;
        }
    }

    let uid_digit = 1;
    if (create_count + uid_num > 10)
        uid_digit = 2;

    let name_digit = 1;
    if (create_count + name_num > 10)
        name_digit = 2;


    for (let idx = 0; idx < create_count; idx++) {
        objMember = new Object();
        objMember.id = uid_pre + getNumberStr(uid_num + idx, uid_digit) + uid_suf;
        objMember.name = name_pre + getNumberStr(name_num + idx, name_digit) + name_suf;
        if (pwd_type == 0) {
            objMember.pwd = pwd_str;
        } else {
            objMember.pwd = getRandomStr(pwd_digit);
        }
        arrMember.push(objMember);
    }

    m_accounts = arrMember;
    showIdAndName(arrMember);

}

function getNumberStr(num, digit) {

    if (digit == 1) {
        return num.toString();
    } else if (digit == 2) {
        if (num < 10) {
            return "0" + num.toString();
        } else {
            return num.toString();
        }
    } else return num.toString();
}

function getRandomStr(digit) {

    let strRand = Math.random().toString(16).substr(2, 11);

    if (strRand.length < digit)
        strRand += Math.random().toString(16).substr(2, 11);


    return strRand.substr(0, digit);
}

function saveMember() {

    var objMember = new Object();
    objMember.mb_emp_fid = $("#mb_emp option:selected").val();
    objMember.mb_time_limit = $("#mb_time_limit").val();
    objMember.mb_vip = $("#mb_vip option:selected").val();
    objMember.mb_accounts = m_accounts;

    if (objMember.mb_accounts == null || objMember.mb_accounts.length < 1) {
        alert("회원정보를 생성해주십시오.");
        return;
    }

    if (objMember.mb_emp_fid == undefined) {
        alert("회원정보를 정확히 입력해주십시오.");
        return;
    }
    let arrIds = new Array();


    for (var idx in m_accounts) {
        arrIds.push(m_accounts[idx].id);

    }

    let setId = new Set(arrIds);
    if (arrIds.length != setId.size) {
        alert("중복된 아이디입니다.");
        return;
    }

    var jsonData = JSON.stringify(objMember);


    if (!confirm("저장하시겠습니까?"))
        return;

    var url = getAppUrl("member_creates");

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
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}

function cancelMember() {

    location.href = getAppUrl("member");
}