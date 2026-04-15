
var stf_level = 0;

function saveStaff(){
    var objStaff = new Object();
    objStaff.stf_fid = $("#stf_fid").text();
    objStaff.stf_uid = $("#stf_uid").val();
    objStaff.stf_pwd = $("#stf_pwd").val();
    objStaff.stf_name = $("#stf_name").val();
    objStaff.stf_color = $("#stf_color").val();
    if($("#stf_memo").length > 0)
        objStaff.stf_memo = $("#stf_memo").val();

    if(stf_level == 9){
        objStaff.stf_emp_fid = 0;
        objStaff.stf_level = 9;

        if(objStaff.stf_uid.length < 1 || objStaff.stf_pwd.length < 1 ||
            objStaff.stf_name.length < 1){
            alert("본사정보를 정확히 입력해주십시오.");
            return;
        }
    } else if (stf_level == 8) {
        objStaff.stf_emp_fid = $("#stf_emp option:selected").val();
        objStaff.stf_level = 8;

        if(objStaff.stf_uid.length < 1 || objStaff.stf_pwd.length < 1 ||
            objStaff.stf_name.length < 1 || objStaff.stf_emp_fid == undefined){
            alert("총판정보를 정확히 입력해주십시오.");
            return;
        }

    } else if (stf_level == 7) {
        objStaff.stf_emp_fid = $("#stf_emp option:selected").val();
        objStaff.stf_level = 7;

        if(objStaff.stf_uid.length < 1 || objStaff.stf_pwd.length < 1 ||
            objStaff.stf_name.length < 1 || objStaff.stf_emp_fid == undefined){
            alert("매장정보를 정확히 입력해주십시오.");
            return;
        }
    } else return;
    
    

    if(!confirm("저장하시겠습니까?"))
        return;

    var jsonData = JSON.stringify(objStaff);
    //console.log(jsonData);

    if(parseInt(objStaff.stf_fid) > 0){    //update
        
        $.ajax({
            type: "POST",
            dataType: "json",
            url:"/staff/staff_modify",
            data: {json_: jsonData},
            success: function(jResult) {
                //console.log(jResult);
                if(jResult.status == "success")
                {
                    gotoStaff();
                } else if(jResult.status == "logout")
                {
                    location.replace('/');
                }
                else if(jResult.status == "fail")
                {
                    if(jResult.code == 6)   
                        alert("중복된 닉네임입니다.");
                    else alert("수정이 실패되었습니다.");
                }
            },
            error:function(request,status,error){
                ///console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }
        });

    } else if(parseInt(objStaff.stf_fid) == 0){ //create

        $.ajax({
            type: "POST",
            dataType: "json",
            url:"/staff/staff_create",
            data: {json_: jsonData},
            success: function(jResult) {
                //console.log(jResult);
                if(jResult.status == "success")
                {
                    gotoStaff();
                } else if(jResult.status == "logout")
                {
                    location.replace('/');
                }
                else if(jResult.status == "fail")
                {
                    if(jResult.code == 5)
                        alert("중복된 아이디입니다.");
                    else if(jResult.code == 6)   
                        alert("중복된 닉네임입니다.");
                    else alert("등록이 실패되었습니다.");
                }
            },
            error:function(request,status,error){
                //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }

        });
    }
    
}

function empChanged(){
    if($("#stf_memo").length < 1)
        return;

    let stf_fid = $("#stf_emp").val();
    let option = $(`#stf_emp option[value='${stf_fid}']`); 
    
    if(option.length > 0){
        $("#stf_memo").val($(option).data("memo"));
    }
    
}

function gotoStaff(){
    if(stf_level == 9)
        location.href = "/staff/company";
    else if(stf_level == 8)
        location.href = "/staff/agency";
    else if(stf_level == 7)
        location.href = "/staff/employee";
    
}