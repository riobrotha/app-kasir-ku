<script>
    const base_url = $('body').data('url');
   
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('cc14b125ee722dc1a2ea', {
        cluster: 'ap1'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
        loadDataQueue();

        VanillaToasts.create({
            title: 'Success!',
            text: data.msg,
            type: 'success',
            positionClass: 'topRight',
            timeout: 2000
        });
    });

    $(function() {

        //load data queue
        loadDataQueue();
    });


    function loadDataQueue() {
        $.ajax({
            url: base_url + 'doctor/home/loadDataQueue',
            method: "GET",
            beforeSend: function() {

            },
            success: function(response) {
                $('.tableQueue').html(response);
                $('#dataTableQueue').DataTable({
                    responsive: true,
                });
            }
        });
    }
</script>