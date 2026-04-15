function saveApp() {
    var objCat = new Object();
    objCat.cat_id = $("#cat_id").val();
    objCat.cat_name = $("#cat_name").val();
    objCat.cat_title = $("#cat_title").val();

    if (objCat.cat_name.length < 1 || objCat.cat_title.length < 1) {
        alert('잘못된 앱정보입니다.');
        return;
    }

    if (!confirm("저장하시겠습니까?"))
        return;

    var jsonData = JSON.stringify(objCat);


    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/staff/app_modify",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                gotoAdmin();
            } else if (jResult.status == "logout") {
                location.replace('/');
            } else if (jResult.status == "fail") {
                if (jResult.code == 6)
                    alert("중복된 네임입니다.");
                else alert("저장이 실패되었습니다.");
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });
}


function createApp() {
    var objCat = new Object();
    objCat.cat_name = $("#cat_name").val();
    objCat.cat_title = $("#cat_title").val();

    if (objCat.cat_name.length < 1 || objCat.cat_title.length < 1) {
        alert('잘못된 앱정보입니다.');
        return;
    }

    if (!confirm("생성하시겠습니까?"))
        return;

    var jsonData = JSON.stringify(objCat);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/staff/app_create",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                gotoAdmin();
            } else if (jResult.status == "logout") {
                location.replace('/');
            } else if (jResult.status == "fail") {
                if (jResult.code == 6)
                    alert("중복된 네임입니다.");
                else alert("생성이 실패되었습니다.");
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}



function gotoAdmin() {
    location.href = "/staff/admin";

}