<script>
    // Fallback include: keeps pages working when cropper integration is absent.
    (function () {
        var input = document.getElementById('customFileUpload');
        var viewer = document.getElementById('viewer');

        if (!input || !viewer) {
            return;
        }

        input.addEventListener('change', function (event) {
            var file = event.target.files && event.target.files[0];
            if (!file) {
                return;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                viewer.setAttribute('src', e.target.result);
            };
            reader.readAsDataURL(file);
        });
    })();
</script>
