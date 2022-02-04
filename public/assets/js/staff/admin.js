$(document).ready(function() {

    requestApp();
});

var mArrApp = null;

function showApp(arrApp) {
    mArrApp = arrApp;
    var tHtml = "";
    if (arrApp != null && arrApp.length > 0) {

        for (var idx in arrApp) {
            tHtml += "<tr>";
            tHtml += "<td>" + arrApp[idx].cat_name + "</td>";
            tHtml += "<td>" + arrApp[idx].cat_title + "</td>";
            tHtml += "<td><button name=\"edit\" value=\"" + arrApp[idx].cat_id + "\">수정</button>";
            if (parseInt(arrApp[idx].cat_stop) == 1)
                tHtml += "<button name=\"stop\" value=\"" + arrApp[idx].cat_id + "\" class=\"stop\">중지</button>";
            else
                tHtml += "<button name=\"permit\" value=\"" + arrApp[idx].cat_id + "\" class=\"permit\">시작</button>";
            tHtml += "<button name=\"delete\" value=\"" + arrApp[idx].cat_id + "\">삭제</button></td>";
            tHtml += "<td><button name=\"clear\" value=\"" + arrApp[idx].cat_id + "\">초기화</button></td>";
            tHtml += "<td><button class=\"shape\" name=\"up\" value=\"" + idx + "\"><i class=\"fas fa-arrow-alt-circle-up\"></i></button>";
            tHtml += "<button class=\"shape\" name=\"down\" value=\"" + idx + "\"><i class=\"fas fa-arrow-alt-circle-down\"></i></button>";
            tHtml += "</td><td><button name=\"design_connect\" value=\"" + arrApp[idx].cat_id + "\">실시간</button>";
            tHtml += "</td></tr>";
        }


    }

    $("#tb-data-id").html(tHtml);
    addEventListner();
}


function addEventListner() {
    var tblBtns = $("#tb-data-id").find("button");
    if (tblBtns == null)
        return;

    var jsonData;
    for (var idx = 0; idx < tblBtns.length; idx++) {

        tblBtns[idx].addEventListener("click", function() {
            if (this.name == "edit") {
                location.href = "/staff/admin_edit/" + this.value;
            } else if (this.name == "delete") {
                jsonData = { "cat_id": this.value };
                requestDeleteApp(jsonData);
            } else if (this.name == "clear") {
                jsonData = { "cat_id": this.value };
                requestClearApp(jsonData);
            } else if (this.name == "up") {
                requestUpApp(this.value);
            } else if (this.name == "down") {
                requestDownApp(this.value);
            } else if (this.name == "permit") {
                if (!confirm("앱을 중지하시겠습니까?"))
                    return;
                jsonData = { "cat_id": this.value, "cat_stop": "1" };
                requestUpdateApp(jsonData);
            } else if (this.name == "stop") {
                if (!confirm("앱을 시작하시겠습니까?"))
                    return;
                jsonData = { "cat_id": this.value, "cat_stop": "0" };
                requestUpdateApp(jsonData);
            } else if (this.name == "design_connect") {
                location.href = "/staff/design_connect/" + this.value;
            }
        });

    }

}


function requestApp() {

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/staff/app_list",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showApp(jResult.data);
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


function requestDeleteApp(sendData) {
    if (!confirm("삭제하시겠습니까?"))
        return;

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/staff/app_delete",
        data: sendData,
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                location.reload();
            } else if (jResult.status == "fail") {
                alert('삭제가 실패되었습니다.');

            } else if (jResult.status == "logout") {
                location.replace('/');
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });

}



function requestClearApp(sendData) {
    if (!confirm("디비를 초기화하시겠습니까?"))
        return;

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/staff/app_clear",
        data: sendData,
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                alert('초기화가 완료되었습니다.');
            } else if (jResult.status == "fail") {
                alert('조작이 실패되었습니다.');

            } else if (jResult.status == "logout") {
                location.replace('/');
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });

}


function requestUpdateApp(sendData) {

    var jsonData = JSON.stringify(sendData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/staff/app_update",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {

                location.reload();
            } else if (jResult.status == "fail") {
                alert('조작이 실패되었습니다.');

            } else if (jResult.status == "logout") {
                location.replace('/');
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
}

function requestUpApp(cat_order) {
    cat_order = parseInt(cat_order);
    if (mArrApp == null || mArrApp.length < 1) {
        return;
    }

    if (cat_order < 1 || cat_order >= mArrApp.length)
        return;

    var sendData = { "cat_id1": mArrApp[cat_order].cat_id, "cat_id2": mArrApp[cat_order - 1].cat_id };
    requestChangeApp(sendData);
}

function requestDownApp(cat_order) {

    cat_order = parseInt(cat_order);
    if (mArrApp == null || mArrApp.length < 1) {
        return;
    }

    if (cat_order < 0 || cat_order + 1 >= mArrApp.length)
        return;

    var sendData = { "cat_id1": mArrApp[cat_order].cat_id, "cat_id2": mArrApp[cat_order + 1].cat_id };
    requestChangeApp(sendData);
}

function requestChangeApp(sendData) {

    $("button.shape").attr("disabled", true);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/staff/app_change",
        data: sendData,
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {

                location.reload();
            } else if (jResult.status == "fail") {
                alert('조작이 실패되었습니다.');
                $("button.shape").attr("disabled", false);
            } else if (jResult.status == "logout") {
                location.replace('/');
            }
        },
        error: function(request, status, error) {
            $("button.shape").attr("disabled", false);
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });
}