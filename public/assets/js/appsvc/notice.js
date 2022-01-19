

$(document).ready(function() {
	

	$("textarea").keydown(function(e) {

	    if(e.keyCode === 9) { // tab was pressed

		     // get caret position/selection
		     var start = this.selectionStart;
		     var end = this.selectionEnd;

		     var $this = $(this);
		     var value = $this.val();

		     // set textarea value to: text before caret + tab + text after caret
		     $this.val(value.substring(0, start)
		                    + "\t"
		                    + value.substring(end));

		     // put caret at right position again (add one for the tab)
		     this.selectionStart = this.selectionEnd = start + 1;

		     // prevent the focus lose
		     e.preventDefault();
		}
	});
});



function saveNotice(){

    $content = $("#notice_content").val();

    var jsonData = { "notice_cat":$("#category_id").text(), "notice_content":$content};

    jsonData = JSON.stringify(jsonData);
    
    $.ajax({
        type: "POST",
        dataType: "json",
        url:"/staff/notice_update",
        data: {json_: jsonData},
        success: function(jResult) {
            //console.log(jResult);
            if(jResult.status == "success")
            {
                alert("저장되었습니다.");
                location.reload();
            } else if(jResult.status == "logout")
            {
                location.replace('/');
            }
            else if(jResult.status == "fail")
            {
                alert("저장이 실패되었습니다.");
            }
        },
        error:function(request,status,error){
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });


}