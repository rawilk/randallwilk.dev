<x-page :title="__('front.legal.disclaimer_title')"
        :description="__('front.legal.disclaimer_description', ['url' => url('/')])"
>
    <x-front.page-banner>
        {{ __('front.legal.disclaimer_title') }}

        <x-slot:content>
            <div class="mt-4 | print:hidden">
                <x-front.link>
                    <div class="flex items-center">
                        <x-heroicon-s-chevron-left class="h-3 w-3 mr-2" />
                        <a href="{{ route('legal.index') }}" class="text-sm">
                            <span>{{ __('front.legal.back_link') }}</span>
                        </a>
                    </div>
                </x-front.link>
            </div>
        </x-slot:content>
    </x-front.page-banner>

    <div class="section section-group pt-0">
        <section>
            <x-front.content-area
                :indent-lists="false"
                :large-text="false"
                headings-as-bullets
            >
                <h2>Definitions</h2>
                <p>
                    The following definitions apply to this Disclaimer and shall have the same meaning
                    regardless of whether they appear in singular or in plural:
                </p>
                <ul>
                    <li>
                        <span class="font-bold">You:</span>
                        the individual accessing the Site, or the company, or
                        other legal entity on behalf of which such individual is
                        accessing or using the Site, as applicable;
                    </li>
                    <li>
                        <span class="font-bold">Randall:</span>
                        (also referred to as "I", "me" or "my" in this Agreement) refers to Randall Wilk, the owner
                        of the site;
                    </li>
                    <li>
                        <span class="font-bold">Site:</span>
                        this website or any associated sites of
                        <x-front.legal-link :url="url('/')" after="." />
                    </li>
                </ul>

                <h2>Disclaimer</h2>
                <p>
                    The information collected on the Site is for general information purposes only. Randall assumes
                    no responsibility for errors or omissions in the contents of the Site. In no event shall Randall
                    be liable for any special, direct, indirect, consequential, or incidental damages or any damages
                    whatsoever, whether in an action of contract, negligence or other tort, arising out of or in
                    connection with the use of the Site or the contents of the Site. Randall reserves the right to
                    make additions, deletions, or modifications to the contents of the Site at any time without
                    prior notice. Randall does not warrant that the Site is free of viruses or other harmful
                    components.
                </p>

                <h2>External Links Disclaimer</h2>
                <p>
                    The Site may contain links to external websites that are not provided or maintained by or in any
                    way affiliated with Randall.
                </p>
                <p>
                    Please note that Randall does not guarantee the accuracy, relevance, timeliness, or completeness
                    of any information on these external sites.
                </p>

                <h2>Errors and Omissions Disclaimer</h2>
                <p>
                    The information given by the Site is for general guidance on matters of interest only. Even if
                    Randall takes every precaution to insure that the content of the Site is both current and
                    accurate, errors can occur. Plus, given the changing nature of laws, rules and regulations,
                    there may be delays, omissions or inaccuracies in the information contained on the Site.
                </p>
                <p>
                    Randall is not responsible for any errors or omissions, or for the results obtained from the use
                    of this information.
                </p>

                <h2>Views Express Disclaimer</h2>
                <p>
                    The Site may contain views and opinions which are those of the authors and do not necessarily
                    reflect the official policy or position of any author, agency, organization, employer or
                    company, including Randall.
                </p>
                <p>
                    Any views expressed on the Site by Randall himself are his views only, and not a reflection of
                    any employer Randall may currently be working for.
                </p>

                <h2>Affiliate Links Disclaimer</h2>
                <p>
                    The Site may sometimes contain affiliate links for third party products on external websites. I may
                    earn a small commission when you click on the links at no additional cost to you. I only recommend
                    products I would use myself and all opinions expressed are my own.
                </p>
                <p>
                    I cannot and will not guarantee the availability, quality, or authenticity of any products or
                    services you purchase from the use of affiliate links.
                </p>

                <h2>No Responsibility Disclaimer</h2>
                <p>
                    The information on the Site is provided with the understanding that Randall is not herein
                    engaged in rendering legal, accounting, tax, or other professional advice and services. As such,
                    it should not be used as a substitute for consultation with professional accounting, tax, legal
                    or other competent advisors.
                </p>
                <p>
                    In no event shall Randall or his suppliers be liable for any special, incidental, indirect, or
                    consequential damages whatsoever arising out of or in connection with your access or use or
                    inability to access or use the Site.
                </p>

                <h2>"Use at Your Own Risk" Disclaimer</h2>
                <p>
                    All information in the Site is provided "as is", with no guarantee of completeness, accuracy,
                    timeliness or of the results obtained from the use of this information, and without warranty of
                    any kind, express or implied, including, but no limited to warranties of performance,
                    merchantability and fitness for a particular purpose.
                </p>
                <p>
                    Randall will not be liable to you or anyone else for any decision made or action taken in
                    reliance on the information given by the Site or for any consequential, special or similar
                    damages, even if advised of the possibility of such damages.
                </p>

                <h2>Contact</h2>
                <p>
                    If you have any questions about this Disclaimer, you can contact me:
                </p>
                <ul>
                    <li>
                        By email:
                        <a href="mailto:{{ config('randallwilk.contact.email') }}">{{ config('randallwilk.contact.email') }}</a>
                    </li>
                </ul>

                <h2>Effective Date</h2>
                <p>
                    This Disclaimer became effective on: August 25, 2022.
                </p>
            </x-front.content-area>
        </section>
    </div>
</x-page>
