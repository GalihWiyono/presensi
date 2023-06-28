<script src="https://code.jquery.com/jquery-3.7.0.slim.js"
    integrity="sha256-7GO+jepT9gJe9LB4XFf8snVOjX3iYNb0FHYr5LI1N5c=" crossorigin="anonymous"></script>
<script type='text/javascript'>
    function logout() {
        sessionStorage.clear()
        console.log("Session Cleared");
    }

    window.onload = function() {
        if (window.jQuery) {
            // jQuery is loaded  
            checkPending();
        }
    }


    function checkPending() {
        console.log("run check pending");

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "/dashboard/pending/checkPending",
            method: 'POST',
            success: function(response) {
                // console.log(response);
                if (response.status) {
                    $('#pendingNotif').removeClass('d-none');
                } else {
                    $('#pendingNotif').addClass('d-none');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
</script>
