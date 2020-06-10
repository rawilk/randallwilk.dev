@if (! config('app.debug') && config('site.googleAnalyticsId'))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('site.googleAnalyticsId') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{ config('site.googleAnalyticsId') }}');
    </script>
@endif
