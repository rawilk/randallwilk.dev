<footer id="footer" class="mt-0" v-cloak>
    <b-container class="my-4">
        <b-row class="py-5">
            <b-col md="6" lg="4" class="mb-5b mb-lg-0">
                <h5 class="text-5 text-transform-none font-weight-semibold text-color-light">
                    Contact Me
                </h5>
                <ul class="list list-icons list-icons-lg mb-4">
                    <li class="mb-1 no-padding-left">
                        <p class="m-0">{{ config('randallwilk.contact_name') }}</p>
                    </li>
                    <li class="mb-1">
                        <i class="far mdi mdi-email text-primary"></i>
                        <p class="m-0">
                            <b-link href="mailto:{{ config('randallwilk.contact_email') }}"
                                    class="link-hover-style-1"
                            >
                                {{ config('randallwilk.contact_email') }}
                            </b-link>
                        </p>
                    </li>
                </ul>

                @component('frontend.components.social-icons')
                    @slot('classes')
                        social-icons-spaced footer-social-icons social-icons-clean social-icons-big social-icons-opacity-light social-icons-icon-light
                    @endslot
                @endcomponent
            </b-col>

            <b-col md="6" lg="6" class="mb-5b mb-lg-0">
                <h5 class="text-5 text-transform-none font-weight-semibold text-color-light">
                    Latest Posts
                </h5>
                <ul class="list list-icons list-icons-sm">
                    @foreach (latestPosts() as $post)
                        <li>
                            <i class="mdi mdi-chevron-right top-1"></i>
                            <a href="{{ getPostLink($post) }}"
                               class="link-hover-style-1"
                            >
                                {{ $post['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </b-col>
            <b-col md="6" lg="2" class="mb-5b mb-lg-0">
                <h5 class="text-5 text-transform-none font-weight-semibold text-color-light">
                    Featured Projects
                </h5>
                <ul class="list list-icons list-icons-sm footer-featured-projects mb-0">
                    @foreach (getFeaturedProjects() as $project)
                        <li>
                            <i class="mdi mdi-chevron-right top-1"></i>
                            <a href="{{ route('frontend.projects.view', ['project' => $project['slug']]) }}"
                               class="link-hover-style-1"
                            >
                                {{ $project['title'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </b-col>
        </b-row>
    </b-container>

    <div class="footer-copyright">
        <b-container class="py-2">
            <b-row class="py-4">
                <b-col lg="8" class="d-flex align-items-center justify-content-center justify-content-lg-start mb-4 mb-lg-0">
                    <p>&copy; Copyright 2016 - {{ now()->year }}. All Rights Reserved.</p>
                </b-col>
                <b-col lg="4" class="d-flex align-items-center justify-content-center justify-content-lg-end">
                    <nav id="sub-menu">
                        <ul>
                            <li>
                                <i class="mdi mdi-chevron-right"></i>
                                <a href="{{ route('frontend.privacy') }}"
                                   class="link-hover-style-1"
                                >
                                    Privacy Policy
                                </a>
                            </li>
                            <li>
                                <i class="mdi mdi-chevron-right"></i>
                                <a href="{{ route('frontend.terms') }}"
                                   class="link-hover-style-1"
                                >
                                    Terms & Conditions
                                </a>
                            </li>
                        </ul>
                    </nav>
                </b-col>
            </b-row>
            <div class="py-1 text-center">
                <p>
                    Proudly hosted with
                    <b-link target="_blank" href="https://forge.laravel.com" class="link-hover-style-1">Laravel Forge</b-link>
                    and
                    <b-link target="_blank" href="https://www.linode.com/?r=a6ec5a5f84fb249e03ed74998e766f3306b10535" class="link-hover-style-1">Linode</b-link>.
                </p>
            </div>
        </b-container>
    </div>
</footer>
