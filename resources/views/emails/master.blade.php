<!doctype html>
<html lang="{!! app()->getLocale() !!}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=[">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>@yield('title')</title>

        <style type="text/css">
            .email-body {
                background: #e7e7ef;
                font-family: sans-serif;
            }

            .email-body > div {
                width: 60%;
                margin: 0 auto;
                padding: 25px 0;
            }

            .email-header {
                text-align: center;
                background: #f7f7f7;
                padding: 20px;
            }

            .email-header img {
                width: 300px;
                max-width: 100%;
            }

            .email-content {
                background: #fff;
                padding: 20px 30px;
            }

            h1 {
                margin-top: 0;
            }

            p, li {
                line-height: 26px;
            }

            a {
                text-decoration: none;
                color: #0088cc;
            }

            a:hover {
                text-decoration: underline;
            }

            ul.list-unstyled {
                padding: 0;
                margin: 0;
                list-style: none;
            }

            ul.list-unstyled li {
                padding: 5px 0;
            }

            .email-footer p {
                font-size: 11px;
                line-height: 14px;
            }

            @media (max-width: 767px) {
                .email-body > div {
                    margin: 0;
                    width: 100%;
                    padding: 0;
                }

                .email-footer {
                    padding-bottom: 15px;
                }
            }
        </style>
    </head>
    <body>
        <div class="email-body">
            <div>
                <div class="email-header">
                    <a href="{{ config('app.url') }}" target="_blank" rel="noopener">
                        <img src="{{ appLogo() }}" alt="{{ config('app.name') }}">
                    </a>
                </div>
                <div class="email-content">
                    @yield('content')
                </div>
                <div class="email-footer">
                    @if (isset($recipient))
                        <p>
                            This email was sent to
                            <a href="mailto:{{ $recipient }}">
                                {{ $recipient }}
                            </a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>