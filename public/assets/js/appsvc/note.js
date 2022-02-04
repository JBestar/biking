$(document).ready(function() {

    addTabKeyListener();

    WebSocketClient.init();

    startWorker();
});

var WebSocketClient;
var mUser;

function addMessage(data, time, type = 0, uid = "") {
    var msg;
    if (type == 1) {
        msg = $('<pre>').text(time + " [" + uid + "]");
        msg.addClass('sent');
    } else {
        msg = $('<pre>').text(time + " [" + uid + "]");
        msg.addClass('recv');
    }

    var messages = $('#messages');
    messages.append(msg);

    msg = $('<pre>').text(data);
    messages.append(msg);

    var msgBox = messages.get(0);
    while (msgBox.childNodes.length > 1000) {
        msgBox.removeChild(msgBox.firstChild);
    }
    msgBox.scrollTop = msgBox.scrollHeight;
};




(function() {

    var ws = null;
    var connected = false;

    var serverUrl;
    var connectionStatus;
    var sendMessage;

    var connectButton;
    var disconnectButton;
    var sendButton;

    var open = function() {
        var url = serverUrl.val();
        ws = new WebSocket(url);
        ws.onopen = onOpen;
        ws.onclose = onClose;
        ws.onmessage = onMessage;
        ws.onerror = onError;

        connectionStatus.text('접속중 ...');
        connectButton.hide();
        disconnectButton.show();
    };

    var close = function() {
        if (ws) {
            // console.log('CLOSING ...');
            ws.close();
        }
    };

    var reset = function() {
        connected = false;
        connectionStatus.text('연결끊김');

        connectButton.show();
        disconnectButton.hide();
        sendMessage.attr('disabled', 'disabled');
        sendButton.attr('disabled', 'disabled');
    };

    var clearLog = function() {
        $('#messages').html('');
    };

    var onOpen = function() {
        // console.log('OPENED: ' + serverUrl.val());
        connected = true;
        connectionStatus.text('접속됨');
        sendMessage.removeAttr('disabled');
        sendButton.removeAttr('disabled');
    };

    var onClose = function() {
        // console.log('CLOSED: ' + serverUrl.val());
        ws = null;
        reset();
    };

    var onMessage = function(event) {

        const data = JSON.parse(event.data);

        if (data.command === "msg") {
            // console.log(event.data);
            //var user = getLevelName(data.level) + ":" + data.uid;
            var user = data.name;

            addMessage(data.msg, getCurrentTm(), 0, user);
        } else if (data.command === "state") {
            if (data.reason == 0) {
                // console.log("sucess!!");
            } else if (data.reason == 1) {
                // console.log("error!!");
            } else if (data.reason == 2) {
                // console.log("logout!!");
                location.reload();
            }
        }


    };

    var onError = function(event) {
        //alert(event.type);
        alert("실시간알림서버에 접속할수 없습니다.");
    };


    WebSocketClient = {
        init: function() {
            serverUrl = $('#serverUrl');
            connectionStatus = $('#connectionStatus');
            sendMessage = $('#sendMessage');

            connectButton = $('#connectButton');
            disconnectButton = $('#disconnectButton');
            sendButton = $('#sendButton');

            connectButton.click(function(e) {
                clearLog();
                reqNotes();
                close();
                open();
            });

            disconnectButton.click(function(e) {
                close();
            });

            sendButton.click(function(e) {
                var msg = $('#sendMessage').val();

                if (msg.length > 0 && mUser != null) {
                    addMessage(msg, getCurrentTm(), 1, mUser.name);
                    ws.send(msg);
                    $('#sendMessage').val('');
                }


            });

            $('#clearMessage').click(function(e) {
                clearLog();
            });

            var isCtrl;
            sendMessage.keyup(function(e) {
                if (e.which === 17) {
                    isCtrl = false;
                }
            }).keydown(function(e) {
                if (e.which === 17) {
                    isCtrl = true;
                }
                if (e.which === 13 && isCtrl === true) {
                    sendButton.click();
                    return false;
                }
            });

            //Connect Server
            connectButton.click();
        }
    };
})();

function showNote(arrNote) {
    var uid = "";
    var name = "";
    var msg = "";
    for (var idx in arrNote) {
        //uid = getLevelName(arrNote[idx].stf_level) + ":" + arrNote[idx].stf_uid;
        uid = arrNote[idx].stf_uid;
        name = arrNote[idx].stf_nickname;
        msg = arrNote[idx].note_text;

        addMessage(msg, arrNote[idx].note_updated, arrNote[idx].type, name);
    }

}




function reqNotes() {

    let url = getAppUrl("note_list");
    // console.log(url);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: url,
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showNote(jResult.data);
                mUser = jResult.user;
            } else if (jResult.status == "fail") {
                alert('잘못된 계정정보입니다.');

            } else if (jResult.status == "logout") {
                location.replace('/');
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });

}




function loopWorker() {

    let tmCurrent = new Date();

    let nCurSec = tmCurrent.getSeconds();
    let nCurMin = tmCurrent.getMinutes();

    if (nCurMin % 10 == 0 && nCurSec == 0) {
        reqKeepAlive();
    }


}