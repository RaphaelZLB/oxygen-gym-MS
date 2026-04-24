@if (session('success'))
    <div class="alert app-alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert app-alert-error alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(function (alertNode) {
                if (typeof bootstrap !== 'undefined') {
                    let alert = bootstrap.Alert.getOrCreateInstance(alertNode);
                    alert.close();
                } else {
                    alertNode.remove();
                }
            });
        }, 3000); // 3000 milliseconds = 3 seconds
    });
</script>

