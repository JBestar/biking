

function saveMember(){

    var objMember = new Object();
    objMember.mb_fid = $("#mb_fid").text();
    objMember.mb_uid = $("#mb_uid").val();
    objMember.mb_pwd = $("#mb_pwd").val();
    objMember.mb_name = $("#mb_name").val();
    objMember.mb_phone = $("#mb_phone").val();
    objMember.mb_emp_fid = $("#mb_emp option:selected").val();
    objMember.mb_time_limit = $("#mb_time_limit").val();
    objMember.mb_vip = $("#mb_vip option:selected").val();
    objMember.mb_prop_1 = $("#mb_prop_1").val();

    if(objMember.mb_uid.length < 1 || objMember.mb_pwd.length < 1 || objMember.mb_emp_fid == undefined){
        alert("회원정보를 정확히 입력해주십시오.");
        return;
    }

    if(objMember.mb_prop_1.length < 1 || parseInt(objMember.mb_prop_1) < 0 || parseInt(objMember.mb_prop_1) > 10 ){
        alert("주문가능수량을 정확히 입력해주십시오.");
        return;
    }

    
    if(parseInt(objMember.mb_fid) > 0){    //update
        requestModifyMember(objMember);

    } else if(parseInt(objMember.mb_fid) == 0){ //create
        requestCreateMember(objMember);
    }
    
}
