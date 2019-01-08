@extends('layouts.frontend-app')
@section('title', 'Privacy Policy')

@push('meta')
    <meta name="description" content="{{ config('randallwilk.default_page_description') }}">
    <meta property="og:type" content="article">
    <meta property="og:description" content="{{ config('randallwilk.default_page_description') }}">
@endpush

@section('content')
    @php
        $breadcrumbs = [
            ['url' => '#', 'display' => 'Privacy Policy']
        ];
    @endphp

    @component('frontend.components.page-header', ['breadcrumbs' => $breadcrumbs])
        @slot('title')
            Privacy Policy
        @endslot
    @endcomponent

    <div class="container py-4">
        <div class="row">
            <div class="col">
                <p>
                    This privacy policy discloses the privacy practices for randallwilk.com. This privacy
                    policy applies solely to information collected by this web site. It will notify
                    you of the following:
                </p>

                <ul class="pl-4">
                    <li>
                        What personally identifiable information is collected from you through the web site,
                        how it is used and with whom it may be shared.
                    </li>
                    <li>
                        What choices are available to you regarding the use of your data.
                    </li>
                    <li>
                        The security procedures in place to protect the misuse of your information.
                    </li>
                    <li>
                        How you can correct any inaccuracies in the information.
                    </li>
                </ul>

                <h2 class="mt-4 mb-2 font-weight-bold text-transform-none">
                    Information Collection, Use, and Sharing
                </h2>
                <p>
                    I am the sole owner of the information collected on this site. I only have access
                    to/collect information that you voluntarily give me via email or other direct
                    contact from you. I will not sell this information to anyone.
                </p>
                <p>
                    I will use this information to respond to you, regarding the reason you contacted me.
                    I will not share your information with any third party, other than as necessary to fulfill
                    your request, e.g. to ship an order.
                </p>
                <p>
                    Unless you ask me no to, I may contact you via email in the future to tell you about specials,
                    new products or services, or changes to this privacy policy.
                </p>

                <h2 class="mt-4 mb-2 font-weight-bold text-transform-none">
                    Your Access to and Control Over Information
                </h2>
                <p>
                    You may opt out of any future contacts from me at any time. You can do
                    the following at any time by contacting me via the email address via
                    the email address or phone number given on my website:
                </p>
                <ul class="pl-4">
                    <li>See what data I have about you, if any.</li>
                    <li>Change/correct any data I have about you.</li>
                    <li>Have me delete any data I have about you.</li>
                    <li>Express any concern you have about my use of your data.</li>
                </ul>

                <h2 class="mt-4 mb-2 font-weight-bold text-transform-none">
                    Security
                </h2>
                <p>
                    I take precautions to protect your information. When you submit sensitive information via the
                    website, your information is protected both online and offline.
                </p>
                <p>
                    Wherever I collect sensitive information (such as credit card data), that information is
                    encrypted and transmitted to me in a secure way. You can verify this by looking for a closed
                    lock icon at the bottom of your web browser, or looking for "https" at the beginning
                    of the address of the web page.
                </p>
                <p>
                    While I use encryption to protect sensitive information online, I also protect your information
                    offline. I am the only person who has access to randallwilk.com and who manages it, so nobody
                    else has access to any information collected by the site. The computers/servers in which
                    personally identifiable information is stored are kept in a secure environment.
                </p>

                <h2 class="mt-4 mb-2 font-weight-bold text-transform-none">
                    Links
                </h2>
                <p>
                    This website contains links to other sites. Please be aware that I am not responsible
                    for the content or privacy practices of such other sites. I encourage anyone to be aware
                    when they leave my site and to read teh privacy statements of any other site that collects
                    personally identifiable information.
                </p>

                <h2 class="mt-4 mb-2 font-weight-bold text-transform-none">
                    Updates
                </h2>
                <p>
                    My privacy policy may change from time to time and all updates will be posted on this page.
                </p>
                <p>
                    If you feel that I am not abiding by this privacy policy, you should contact me immediately
                    via email at {{ config('randallwilk.contact_email') }}.
                </p>
            </div>
        </div>
    </div>
@endsection