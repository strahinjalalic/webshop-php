<script src="js/jquery.js"></script>
<script src="js/scripts.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="https://js.pusher.com/5.1/pusher.min.js"></script>
<script src="js/plugins/morris/raphael.min.js"></script>
<script src="js/plugins/morris/morris.min.js"></script>
<script src="js/plugins/morris/morris-data.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha256-ENFZrbVzylNbgnXx0n3I1g//2WeO47XxoPe0vkp3NC8=" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha256-3blsJd4Hli/7wCQ+bmgXfOdK7p/ZUMtPXY08jmxSSgk=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        let pusher = new Pusher('167c9c214517f32c90ce', {
            cluster: 'eu',
            forceTLS: true
        });

        let channel = pusher.subscribe('notifications');
        channel.bind('new_user', function(notification) {
            var message = notification.message;
            toastr.options.closeButton = true;
            toastr.options.showDuration = 1000;
            toastr.options.hideDuration = 1000;
            toastr.options.timeOut = 3000;
            toastr.options.extendedTimeOut = 1000;
            toastr.info(`${message} just registered!`, "New notification");
        });
    });
</script>
</body>
</html>}
