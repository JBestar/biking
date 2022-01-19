$(document).ready(function() {

    var logged = getCookie("logged");

    if (logged != "yes") {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/staff/staff_logout",
            success: function(jResult) {
                location.reload();
            },
            error: function(request, status, error) {}
        });
    }

});