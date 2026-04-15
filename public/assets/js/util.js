const LEVEL_ADMIN = 10;
const LEVEL_COMPANY = 9;
const LEVEL_AGENCY = 8;
const LEVEL_EMPLOYEE = 7;
const LEVEL_MIN = 1;

function setCookie(name, value, expiredays) {

    if (expiredays) {
        var date = new Date();
        date.setTime(date.getTime() + (expiredays * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    } else {
        var expires = "; expires=0";
    }

    document.cookie = name + "=" + value + expires + "; path=/";

}


function getCookie(name) {

    var cName = name + "=";
    var x = 0;
    while (x <= document.cookie.length) {
        var y = (x + cName.length);
        if (document.cookie.substring(x, y) == cName) {
            if ((endOfCookie = document.cookie.indexOf(";", y)) == -1)
                endOfCookie = document.cookie.length;
            return unescape(document.cookie.substring(y, endOfCookie));
        }

        x = document.cookie.indexOf(" ", x) + 1;
        if (x == 0)
            break;
    }
    return "";
}

function addTabKeyListener() {
    $("textarea").keydown(function(e) {

        if (e.keyCode === 9) { // tab was pressed

            // get caret position/selection
            var start = this.selectionStart;
            var end = this.selectionEnd;

            var $this = $(this);
            var value = $this.val();

            // set textarea value to: text before caret + tab + text after caret
            $this.val(value.substring(0, start) +
                "\t" +
                value.substring(end));

            // put caret at right position again (add one for the tab)
            this.selectionStart = this.selectionEnd = start + 1;

            // prevent the focus lose
            e.preventDefault();
        }
    });
}

function getCurrentTm() {
    var today = new Date();
    var date = today.getFullYear() + '-' + toDigitalFormat(today.getMonth() + 1) + '-' + toDigitalFormat(today.getDate());
    var time = toDigitalFormat(today.getHours()) + ":" + toDigitalFormat(today.getMinutes()) + ":" + toDigitalFormat(today.getSeconds());
    return date + ' ' + time;;
}

function toDigitalFormat(num) {
    if (num < 10) {
        num = '0' + num;
    }
    return num;
}

function getLevelName(level) {
    var msg = "";
    if (level > 9) {
        msg = "관리자";
    } else if (level == 9) {
        msg = "본사";
    } else if (level == 8) {
        msg = "총판";
    } else if (level == 7) {
        msg = "매장";
    }
    return msg;
}




function reqKeepAlive() {

    let url = getAppUrl("keepalive");

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {

            } else if (jResult.status == "fail") {
                // alert('잘못된 계정정보입니다.');

            } else if (jResult.status == "logout") {
                location.reload();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });

}


/*=============MainLoop=============== */

var mWorker;
// worker 실행
function startWorker() {

    // Worker 지원 유무 확인
    if (!!window.Worker) {

        // 실행하고 있는 워커 있으면 중지시키기
        if (mWorker) {
            stopWorker();
        }

        mWorker = new Worker('/assets/js/lib/worker.js');
        mWorker.postMessage('워커 실행'); // 워커에 메시지를 보낸다.

        // 워커로 부터 메시지를 수신한다.
        mWorker.onmessage = function(e) {
            loopWorker();

        };
    }

}


// worker 중지
function stopWorker() {


    if (mWorker) {
        mWorker.terminate();
        mWorker = null;
    }

}