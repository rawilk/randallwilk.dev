<b-affix tag="header" id="header" class="header-effect-shrink" fix-to="body" :minimum-offset="15">
    <div class="header-body border-top-0">
        <div class="header-top">
            <b-container>
                <div class="header-row py-2">
                    <div class="header-column justify-content-start">
                        <div class="header-row">
                            <nav class="header-nav-top">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <span class="ws-nowrap pl-0">
                                            <i class="mdi mdi-email"></i>
                                            {{ config('randallwilk.contact_email') }}
                                        </span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="header-column justify-content-end">
                        <div class="header-row">
                            @component('frontend.components.social-icons')
                                @slot('classes')
                                    header-social-icons d-none d-sm-block social-icons-clean
                                @endslot
                            @endcomponent
                        </div>
                    </div>
                </div>
            </b-container>
        </div>

        <b-container class="header-container">
            <div class="header-row">
                <div class="header-column">
                    <div class="header-row">
                        <div class="header-logo">
                            <a href="{{ route('frontend.home') }}">
                                <b-img src="{{ appLogo() }}" alt="{{ config('app.name') }}"
                                >
                                </b-img>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="header-column justify-content-end">
                    <div class="header-row">
                        <div class="header-nav header-nav-line header-nav-top-line header-nav-top-line-with-border order-2 order-lg-1">
                            <div class="header-nav-main header-nav-main-square header-nav-main-effect-2 header-nav-main-sub-effect-1">
                                <b-navbar toggleable="lg">
                                    <b-navbar-toggle target="header-nav"></b-navbar-toggle>

                                    <b-collapse is-nav id="header-nav">
                                        <b-navbar-nav class="nav" id="mainNav">
                                            <b-nav-item href="{!! route('frontend.about') !!}" {{ isActiveRoute('frontend.about') }}>
                                                About
                                            </b-nav-item>
                                            <b-nav-item href="{!! route('frontend.projects') !!}" {{ isActiveRoute('frontend.projects') }}>
                                                Projects
                                            </b-nav-item>
                                            <b-nav-item href="{!! route('frontend.resume') !!}" {{ isActiveRoute('frontend.resume') }}>
                                                Resume
                                            </b-nav-item>
                                            <b-nav-item href="{!! route('frontend.blog') !!}" {{ isActiveRoute('frontend.blog') }}>
                                                Blog
                                            </b-nav-item>
                                            <b-nav-item href="{!! route('frontend.contact') !!}" {{ isActiveRoute('frontend.contact') }}>
                                                Contact
                                            </b-nav-item>
                                        </b-navbar-nav>
                                    </b-collapse>
                                </b-navbar>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-container>
    </div>
</b-affix>
