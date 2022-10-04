{{--
    This alert div is only here to help show flashed session errors from our window we open for socialite
    since we lose them after closing the window. I'm not really a fan of doing this, but I'm not sure
    of any other way to do this right now...
--}}
<x-alert :type="\Rawilk\LaravelBase\Components\Alerts\Alert::ERROR" id="socialite-error" class="hidden mb-4" dismiss>
    <div id="socialite-error-content"></div>
</x-alert>

<script>
    function notifyUserOfError() {
        try {
            const errorMessage = localStorage.getItem('socialite.error');
            const alert = document.getElementById('socialite-error');

            if (errorMessage) {
                document.getElementById('socialite-error-content').innerHTML = `<p>${errorMessage}</p>`;
                alert.classList.remove('hidden');

                document.getElementById('{{ $errorWrapper ?? 'form-wrapper' }}').prepend(alert);

                localStorage.removeItem('socialite.error');
            } else {
                alert.remove();
            }
        } catch {
        }
    }

    notifyUserOfError();
</script>
