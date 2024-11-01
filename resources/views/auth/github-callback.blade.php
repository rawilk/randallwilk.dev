@php
    use App\Enums\SessionAlert;
    use Rawilk\ProfileFilament\Filament\Pages\Profile\ProfileInfo;

    $defaultRedirect = ProfileInfo::getUrl(panel: $panelId);

    session()->reflash();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Logged in using GitHub!</title>
    </head>
    <body>
        <h1>Redirecting...</h1>

        <script>
            @if (SessionAlert::Error->exists())
                try {
                    window.localStorage.setItem(
                        'socialite.message',
                        JSON.stringify(@js([
                            'type' => SessionAlert::Error->value,
                            'message' => SessionAlert::Error->message()
                        ]))
                    );
                } catch {
                }
            @endif

            if (window.opener && window.opener !== window) {
                // Let our opener know we are ready to redirect.
                window.opener.postMessage({
                    type: 'AUTH_COMPLETE',
                    redirectUrl: @js($redirect ?? $defaultRedirect),
                });
            } else {
                window.location.replace(@js(session('next', $defaultRedirect)));
            }
        </script>
    </body>
</html>
