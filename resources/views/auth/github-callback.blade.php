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
            if (window.opener && window.opener !== window) {
                // This is an auth popup. We can close this window and
                // the parent window will take care of the user.
                window.close();
            } else {
                window.location.replace('{{ session('next', route('profile.authentication')) }}');
            }
        </script>
    </body>
</html>
