<section id="email" class="section pb-0">
    <div class="wrap">
        <h1 class="title-2xl">Contact</h1>
    </div>

    <div class="wrap items-start">
        <div class="markup markup-lists links-underline links-black">
            <p class="text-lg">
                If you would like to discuss a consulting or speaking arrangement with me, you may email me at
                <a href="mailto:{!! config('site.contact.email') !!}">{!! config('site.contact.email') !!}</a>
                and I will do my best to respond within 24 hours.
            </p>

            <p class="text-lg">
                I also have various social networking profiles:
            </p>

            <ul class="sm:pl-10">
                @foreach (config('site.contact.social') as $name => $url)
                    <li>
                        {!! $name !!}: <a href="{!! $url !!}" target="_blank" rel="nofollow noreferrer noopener">{!! $url !!}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
