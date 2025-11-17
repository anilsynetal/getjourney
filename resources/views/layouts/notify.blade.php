<audio style="display: none;" id="audio" src="{{ asset('assets/bell.wav') }}"></audio>
<link href="{{ asset('assets/global/css/iziToast.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/global/css/iziToast_custom.css') }}" rel="stylesheet">
<script src="{{ asset('assets/global/js/iziToast.min.js') }}"></script>
<script src="{{ asset('assets/js/pusher.min.js') }}"></script>

<script>
    function checkJobStatus(jobId) {
        let interval = setInterval(function() {
            $.get('/settings/translation-job/' + jobId, function(response) {
                if (response.status === 'completed') {
                    clearInterval(interval);
                    triggerToaster('success', "Translation file generated successfully!");
                } else if (response.status === 'failed') {
                    clearInterval(interval);
                    triggerToaster('error', "Translation failed: " + response.message);
                }
            });
        }, 5000);
    }
</script>
@if (session()->has('job_id'))
    <script>
        checkJobStatus('{{ session('job_id') }}');
    </script>
@endif
<script type="text/javascript">
    Pusher.logToConsole = false;

    var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
        cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
        encrypted: true
    });

    var receiver = "{{ Auth::user()->id }}";
    var channel = pusher.subscribe("chat-channel");

    channel.bind("message-event", function(data) {

        if (receiver == data.message.user_id) {
            const bell_count = parseInt($("#bell-count").text()) || 0;
            const unread_count = parseInt($("#unread-count").text()) || 0;

            $("#bell-count").text(bell_count + 1);
            $("#unread-count").text(unread_count + 1);

            // Truncate description to 25 characters
            function truncate(str, n) {
                return (str.length > n) ? str.substring(0, n) + "..." : str;
            }

            var timeAgo = new Date(data.message.notification.created_at).toLocaleString();
            var truncatedDescription = truncate(data.message.notification.description, 25);

            var html = `
                <a href="#"
                    class="text-reset notification-item">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${data.message.notification.title}</h6>
                            <div class="font-size-13 text-muted">
                                <p class="mb-1">${truncatedDescription}</p>
                                <p class="mb-0">
                                    <i class="mdi mdi-clock-outline"></i> <span>${timeAgo}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </a>`;

            html = html.replace("__USER_ID__", data.message.user_id);
            $("#notification-section-top").find('.simplebar-content').prepend(html);
            play();
        }
    });

    function play() {
        var audio = document.getElementById("audio");
        audio.play();
    }
</script>


<script>
    "use strict";
    const colors = {
        success: '#28c76f',
        error: '#eb2222',
        warning: '#ff9f43',
        info: '#1e9ff2',
    }

    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-times-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-exclamation-circle',
    }

    const notifications = @json(session('notify', []));
    const errors = @json(@$errors ? collect($errors->all())->unique() : []);


    const triggerToaster = (status, message) => {
        iziToast[status]({
            title: status.charAt(0).toUpperCase() + status.slice(1),
            message: message,
            position: "topRight",
            backgroundColor: '#fff',
            icon: icons[status],
            iconColor: colors[status],
            progressBarColor: colors[status],
            titleSize: '1rem',
            messageSize: '1rem',
            titleColor: '#474747',
            messageColor: '#a2a2a2',
            transitionIn: 'obunceInLeft'
        });
    }

    if (notifications.length) {
        notifications.forEach(element => {
            triggerToaster(element[0], element[1]);
        });
    }

    if (errors.length) {
        errors.forEach(error => {
            triggerToaster('error', error);
        });
    }

    function notify(status, message) {
        if (typeof message == 'string') {
            triggerToaster(status, message);
        } else {
            $.each(message, (i, val) => triggerToaster(status, val));
        }
    }

    //Delete Alert
    function deleteAlert(callback) {
        Swal.fire({
            title: "@lang('translation.are_you_sure')",
            text: "@lang('translation.you_wont_be_able_to_revert_this')",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#556ee6",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "@lang('translation.yes_delete_it')",
            cancelButtonText: "@lang('translation.no_cancel')",
        }).then(function(result) {
            if (result.value) {
                callback();
            }
        });
    }

    //Sync Alert
    function syncAlert(callback) {
        Swal.fire({
            title: "@lang('translation.are_you_sure')",
            text: "@lang('translation.you_want_to_sync')",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#556ee6",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "@lang('translation.yes_sync_it')",
            cancelButtonText: "@lang('translation.no_cancel')",
        }).then(function(result) {
            if (result.value) {
                callback();
            }
        });
    }

    //Restore Alert
    function restoreAlert(callback) {
        Swal.fire({
            title: "@lang('translation.are_you_sure')",
            text: "@lang('translation.you_want_to_restore')",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#556ee6",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "@lang('translation.yes_restore_it')",
            cancelButtonText: "@lang('translation.no_cancel')",
        }).then(function(result) {
            if (result.value) {
                callback();
            }
        });
    }

    //Update status Alert
    function updateStatusAlert(callback) {
        Swal.fire({
            title: "@lang('translation.are_you_sure')",
            text: "@lang('translation.you_want_to_update_status')",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#556ee6",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "@lang('translation.yes_update_it')",
            cancelButtonText: "@lang('translation.no_cancel')",
        }).then(function(result) {
            if (result.value) {
                callback();
            }
        });
    }
</script>
