var stf_level = 0;

function requestStaff() {
    if (stf_level < 7 || stf_level > 9)
        return;

    var strSearch = $('#search_txt').val();

    var send_data = { level: stf_level, search: strSearch };
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/staff/staff_list",
        data: send_data,
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showStaff(jResult.data, jResult.cats);
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

function requestUpdateStaff(jsData) {

    var jsonData = JSON.stringify(jsData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/staff/staff_update",
        data: { json_: jsonData },
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                requestStaff();
            } else if (jResult.status == "fail") {
                alert('변경이 실패되었습니다.');
            } else if (jResult.status == "logout") {
                location.replace('/');
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}

function requestDeleteStaff(jsData) {

    if (stf_level == 9) {
        if (!confirm("하부총판, 매장까지 모두 삭제합니다. 계속하시겠습니까?"))
            return;
    } else if (stf_level == 8) {
        if (!confirm("하부매장까지 모두 삭제합니다. 계속하시겠습니까?"))
            return;
    } else if (stf_level == 7) {
        if (!confirm("삭제하시겠습니까?"))
            return;
    } else return;

    var jsonData = JSON.stringify(jsData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/staff/staff_delete",
        data: { json_: jsonData },
        success: function(jResult) {
            //console.log(jResult);

            if (jResult.status == "success") {
                requestStaff();
            } else if (jResult.status == "fail") {
                alert('삭제가 실패되었습니다.');
            } else if (jResult.status == "logout") {
                location.replace('/');
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}



function showStaff(arrStaff, arrCat) {
    var tHtml = "";
    if (arrStaff != null && arrStaff.length > 0 && arrCat != null) {

        var objCatInfo = null;
        for (var idx in arrStaff) {
            if (arrStaff[idx].stf_color != null && arrStaff[idx].stf_color.length > 0) {
                tHtml += "<tr bgcolor=\"" + arrStaff[idx].stf_color + "\">";
            } else {
                tHtml += "<tr>";
            }
            tHtml += "<td>" + (parseInt(idx) + 1) + "</td>";
            tHtml += "<td>" + arrStaff[idx].stf_uid + "</td>";
            tHtml += "<td>" + arrStaff[idx].stf_name + "</td>";
            if (arrStaff[idx].stf_time_join.length > 10)
                tHtml += "<td>" + arrStaff[idx].stf_time_join.substr(0, 10) + "</td>";
            else tHtml += "<td></td>";

            if (arrStaff[idx].stf_time_last.length > 10)
                tHtml += "<td>" + arrStaff[idx].stf_time_last.substr(0, 10) + "</td>";
            else tHtml += "<td></td>";

            if (parseInt(arrStaff[idx].stf_state_active) == 1)
                tHtml += "<td><button name=\"permit\" value=\"" + arrStaff[idx].stf_fid + "\" class=\"permit\">승인</button></td>";
            else
                tHtml += "<td><button name=\"stop\" value=\"" + arrStaff[idx].stf_fid + "\" class=\"stop\">차단</button></td>";

            tHtml += "<td><button name=\"edit\" value=\"" + arrStaff[idx].stf_fid + "\">수정</button>";
            tHtml += "<button name=\"delete\" value=\"" + arrStaff[idx].stf_fid + "\" >삭제</button></td>";

            tHtml += "<td>";

            for (var idy in arrCat) {
                objCatInfo = getCatInfo(arrCat[idy], arrStaff[idx]);
                if (objCatInfo != null) {
                    tHtml += "<button name=\"" + objCatInfo.name + "\" value=\"" + arrStaff[idx].stf_fid + "\" ";
                    tHtml += " class=\"" + objCatInfo.class + "\" >";
                    tHtml += arrCat[idy].cat_title + "</button>";
                }
            }
            tHtml += "</td></tr>";
        }
    }

    $("#tb-data-id").html(tHtml);
    addEventListner();
}


function getCatInfo($objCat, objStaff) {


    var butName = "",
        butClass = "";

    switch (parseInt($objCat.cat_id)) {
        case 1:
            butName = "app_01";
            butClass = parseInt(objStaff.stf_app_01) == 1 ? "permit" : "disable";
            break;
        case 2:
            butName = "app_02";
            butClass = parseInt(objStaff.stf_app_02) == 1 ? "permit" : "disable";
            break;
        case 3:
            butName = "app_03";
            butClass = parseInt(objStaff.stf_app_03) == 1 ? "permit" : "disable";
            break;
        case 4:
            butName = "app_04";
            butClass = parseInt(objStaff.stf_app_04) == 1 ? "permit" : "disable";
            break;
        case 5:
            butName = "app_05";
            butClass = parseInt(objStaff.stf_app_05) == 1 ? "permit" : "disable";
            break;
        case 6:
            butName = "app_06";
            butClass = parseInt(objStaff.stf_app_06) == 1 ? "permit" : "disable";
            break;
        case 7:
            butName = "app_07";
            butClass = parseInt(objStaff.stf_app_07) == 1 ? "permit" : "disable";
            break;
        case 8:
            butName = "app_08";
            butClass = parseInt(objStaff.stf_app_08) == 1 ? "permit" : "disable";
            break;
        case 9:
            butName = "app_09";
            butClass = parseInt(objStaff.stf_app_09) == 1 ? "permit" : "disable";
            break;
        case 10:
            butName = "app_10";
            butClass = parseInt(objStaff.stf_app_10) == 1 ? "permit" : "disable";
            break;
        case 11:
            butName = "app_11";
            butClass = parseInt(objStaff.stf_app_11) == 1 ? "permit" : "disable";
            break;
        case 12:
            butName = "app_12";
            butClass = parseInt(objStaff.stf_app_12) == 1 ? "permit" : "disable";
            break;
        case 13:
            butName = "app_13";
            butClass = parseInt(objStaff.stf_app_13) == 1 ? "permit" : "disable";
            break;
        case 14:
            butName = "app_14";
            butClass = parseInt(objStaff.stf_app_14) == 1 ? "permit" : "disable";
            break;
        case 15:
            butName = "app_15";
            butClass = parseInt(objStaff.stf_app_15) == 1 ? "permit" : "disable";
            break;
        case 16:
            butName = "app_16";
            butClass = parseInt(objStaff.stf_app_16) == 1 ? "permit" : "disable";
            break;
        case 17:
            butName = "app_17";
            butClass = parseInt(objStaff.stf_app_17) == 1 ? "permit" : "disable";
            break;
        case 18:
            butName = "app_18";
            butClass = parseInt(objStaff.stf_app_18) == 1 ? "permit" : "disable";
            break;
        case 19:
            butName = "app_19";
            butClass = parseInt(objStaff.stf_app_19) == 1 ? "permit" : "disable";
            break;
        case 20:
            butName = "app_20";
            butClass = parseInt(objStaff.stf_app_20) == 1 ? "permit" : "disable";
            break;
        case 21:
            butName = "app_21";
            butClass = parseInt(objStaff.stf_app_21) == 1 ? "permit" : "disable";
            break;
        case 22:
            butName = "app_22";
            butClass = parseInt(objStaff.stf_app_22) == 1 ? "permit" : "disable";
            break;
        case 23:
            butName = "app_23";
            butClass = parseInt(objStaff.stf_app_23) == 1 ? "permit" : "disable";
            break;
        case 24:
            butName = "app_24";
            butClass = parseInt(objStaff.stf_app_24) == 1 ? "permit" : "disable";
            break;
        case 25:
            butName = "app_25";
            butClass = parseInt(objStaff.stf_app_25) == 1 ? "permit" : "disable";
            break;
        case 26:
            butName = "app_26";
            butClass = parseInt(objStaff.stf_app_26) == 1 ? "permit" : "disable";
            break;
        case 27:
            butName = "app_27";
            butClass = parseInt(objStaff.stf_app_27) == 1 ? "permit" : "disable";
            break;
        case 28:
            butName = "app_28";
            butClass = parseInt(objStaff.stf_app_28) == 1 ? "permit" : "disable";
            break;
        case 29:
            butName = "app_29";
            butClass = parseInt(objStaff.stf_app_29) == 1 ? "permit" : "disable";
            break;
        case 30:
            butName = "app_30";
            butClass = parseInt(objStaff.stf_app_30) == 1 ? "permit" : "disable";
            break;
        default:
            break;
    }

    if (butName.length > 0) {
        var objCatInfo = new Object();
        objCatInfo.name = butName;
        objCatInfo.class = butClass;
        return objCatInfo;
    } else return null;


}


function addEventListner() {
    var tblBtns = $("#tb-data-id").find("button");
    if (tblBtns == null)
        return;

    var jsonData;
    for (var idx = 0; idx < tblBtns.length; idx++) {

        tblBtns[idx].addEventListener("click", function() {

            if (this.name == "permit") {
                jsonData = { "stf_fid": this.value, "stf_state_active": 0 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "stop") {
                jsonData = { "stf_fid": this.value, "stf_state_active": 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "edit") {
                if (stf_level == 9)
                    location.href = "/staff/company_edit/" + this.value;
                else if (stf_level == 8)
                    location.href = "/staff/agency_edit/" + this.value;
                else if (stf_level == 7)
                    location.href = "/staff/employee_edit/" + this.value;
            } else if (this.name == "delete") {
                jsonData = { "stf_fid": this.value };
                requestDeleteStaff(jsonData);
            } else if (this.name == "app_01") {
                jsonData = { "stf_fid": this.value, "stf_app_01": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_02") {
                jsonData = { "stf_fid": this.value, "stf_app_02": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_03") {
                jsonData = { "stf_fid": this.value, "stf_app_03": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_04") {
                jsonData = { "stf_fid": this.value, "stf_app_04": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_05") {
                jsonData = { "stf_fid": this.value, "stf_app_05": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_06") {
                jsonData = { "stf_fid": this.value, "stf_app_06": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_07") {
                jsonData = { "stf_fid": this.value, "stf_app_07": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_08") {
                jsonData = { "stf_fid": this.value, "stf_app_08": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_09") {
                jsonData = { "stf_fid": this.value, "stf_app_09": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_10") {
                jsonData = { "stf_fid": this.value, "stf_app_10": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_11") {
                jsonData = { "stf_fid": this.value, "stf_app_11": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_12") {
                jsonData = { "stf_fid": this.value, "stf_app_12": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_13") {
                jsonData = { "stf_fid": this.value, "stf_app_13": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_14") {
                jsonData = { "stf_fid": this.value, "stf_app_14": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_15") {
                jsonData = { "stf_fid": this.value, "stf_app_15": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_16") {
                jsonData = { "stf_fid": this.value, "stf_app_16": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_17") {
                jsonData = { "stf_fid": this.value, "stf_app_17": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_18") {
                jsonData = { "stf_fid": this.value, "stf_app_18": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_19") {
                jsonData = { "stf_fid": this.value, "stf_app_19": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_20") {
                jsonData = { "stf_fid": this.value, "stf_app_20": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_21") {
                jsonData = { "stf_fid": this.value, "stf_app_21": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_22") {
                jsonData = { "stf_fid": this.value, "stf_app_22": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_23") {
                jsonData = { "stf_fid": this.value, "stf_app_23": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_24") {
                jsonData = { "stf_fid": this.value, "stf_app_24": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_25") {
                jsonData = { "stf_fid": this.value, "stf_app_25": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_26") {
                jsonData = { "stf_fid": this.value, "stf_app_26": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_27") {
                jsonData = { "stf_fid": this.value, "stf_app_27": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_28") {
                jsonData = { "stf_fid": this.value, "stf_app_28": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_29") {
                jsonData = { "stf_fid": this.value, "stf_app_29": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            } else if (this.name == "app_30") {
                jsonData = { "stf_fid": this.value, "stf_app_30": this.className == "permit" ? 0 : 1 };
                requestUpdateStaff(jsonData);
            }
        });

    }
}