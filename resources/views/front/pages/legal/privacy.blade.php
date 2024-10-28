<x-page :title="__('front.privacy.title')"
        :description="__('front.privacy.description')"
>
    <x-front.page-banner>
        {{ __('front.privacy.banner') }}

        <x-slot:content>
            <div class="mt-4 | print:hidden">
                <x-front.link>
                    <div class="flex items-center">
                        <x-heroicon-s-chevron-left class="h-3 w-3 mr-2" />
                        <a href="{{ route('legal.index') }}" class="text-sm" wire:navigate>
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
                <h2>General terms</h2>

                <p>
                    Randall Wilk ("Randall") attaches great importance to the protection of your privacy and
                    personal data. I use your personal data solely in accordance with privacy laws and other
                    relevant legislation in force.
                </p>
                <p>
                    With this Privacy Statement, Randall wants to point out to you any processing operations on
                    this data and on your rights. By using the website located at
                    <x-front.legal-link :url="url('/')" after="," />
                    and all associated sites linked to
                    <x-front.legal-link :url="url('/')" />
                    by Randall, (collectively, the "Site") you declare that you have read this privacy policy and
                    that you explicitly agree to its content as well as to the processing itself.
                </p>
                <p>
                    It is possible that this Privacy Statement is subject to adjustments and changes in the future.
                    It is up to you to consult this document on a regular basis. Any substantial change will always
                    be clearly communicated on the Site.
                </p>

                <h2>Definitions</h2>
                <p>
                    The following definitions apply to this Privacy Policy and shall have the same meaning
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
                        <span class="font-bold">Account:</span>
                        a unique account created for You to access the Site or parts of my Service;
                    </li>
                    <li>
                        <span class="font-bold">Service:</span>
                        refers to the Site;
                    </li>
                    <li>
                        <span class="font-bold">Personal Data:</span>
                        any information that relates to an identified or identifiable individual.
                    </li>
                    <li>
                        <span class="font-bold">Cookies:</span>
                        small files that are placed on Your computer, mobile device or any other device by a
                        website, containing the details of Your browsing history on that website among its many
                        uses.
                    </li>
                    <li>
                        <span class="font-bold">Site:</span>
                        this website or any associated sites of
                        <x-front.legal-link :url="url('/')" after="." />
                    </li>
                </ul>

                <h2>Who processes your personal data?</h2>

                <p>The website randallwilk.dev is an initiative of:</p>
                <address>
                    Randall Wilk<br>
                    Email:
                    <a href="mailto:{{ config('randallwilk.contact.email') }}">{{ config('randallwilk.contact.email') }}</a>
                </address>

                <h2>Collected personal data</h2>
                <p>
                    Randall commits to only process data that are relevant and necessary for the purpose for
                    which they were collected.
                </p>
                <h3>Personal data communicated to me:</h3>
                <ul>
                    <li>Type 1: without any registration: your IP-address</li>
                    <li>Type 2: when creating an account: your name, your email address, and password</li>
                    <li>Type 3: data obtained by cookies placed on the website</li>
                </ul>
                <h3>Randall can collect your data through different means:</h3>
                <ul>
                    <li>by placing cookies;</li>
                    <li>during your registration and use of the website</li>
                </ul>

                <h2>For what purposes are my personal data used?</h2>
                <p>
                    Randall collects personal data to give you a safe, optimal and personalized user
                    experience. The collection of personal data is more comprehensive to the extent that you make
                    more intensive use of the Site and my online services.
                </p>
                <p>
                    Data processing is crucial to the operation of the Site and the associated services. The
                    processing is done exclusively for these specific purposes:
                </p>
                <ul>
                    <li>The detection of and the protection against fraud, errors and/or criminal behavior.</li>
                    <li>Marketing purposes</li>
                    <li>
                        Maintaining and improving the Site and the creation of anonymous statistics (the identity
                        of particular persons or companies will not be traceable) based on the "legitimate interest"
                        of Randall to improve the service and website.
                    </li>
                    <li>
                        Managing your account on the website
                    </li>
                </ul>
                <p>
                    When visiting the Site some data are collected for statistical purposes. Such data are necessary
                    to optimise the use of the Site. These data are: IP address, probable place of consultation,
                    hour and day of consultation, which pages were visited. When you visit the Site you agree to
                    this data
                    collection for statistical purposes as described above.
                </p>
                <p>
                    The User always provides personal data to Randall and can in this way exercise a certain
                    control. If certain data are incomplete or apparently incorrect, Randall retains the right
                    to postpone certain expected actions temporarily or permanently.
                </p>
                <p>
                    The personal data will only be processed for internal use within the Site.
                    <br>
                    I can assure you that personal data will not be sold, transmitted or be communicated to third
                    parties who are associated with me. Randall has taken all possible legal and technical
                    precautions to prevent unauthorised access and use.
                </p>
                <h3>Legal requirements</h3>
                <p>
                    In extraordinary circumstances, Randall may be obliged to disclose your data following a
                    court order or to comply with mandatory law and/or regulation. Randall will, if reasonably
                    possible, try to inform you in advance unless revealing this information is subject to legal
                    constraints.
                </p>

                <h2>Cookies</h2>
                <h3>What is a cookie?</h3>
                <p>
                    A "cookie" is a small text file that is sent from the server of the Site and is stored on your
                    computer or mobile phone when visiting the Site. This way, I can remember your preferences when
                    visiting the Site. Randall can only read the information stored these cookies.
                </p>
                <h3>Why do I use cookies?</h3>
                <p>
                    The Site uses cookies and similar technologies to distinguish your preferences from those of
                    the other visitors to my website. Cookies allow me to offer you a better experience and to
                    optimize your visit to the Site.
                </p>
                <p>
                    Even though I do not focus my website on certain parts of the European Union and am not
                    obligated to request your permission to use or store cookies and similar technologies on your
                    computer or mobile device, I still want to inform you about all the cookies that I use and their
                    purposes.
                </p>
                <h3>Different kinds of cookies used on my website:</h3>
                <ul>
                    <li>
                        Essential website cookies: these cookies are necessary to give you as a visitor an optimal
                        experience on my website.
                    </li>
                    <li>
                        XSRF-token: this cookie validates the user session and is necessary to guarantee safety
                        during the website visit to avoid cross-site request forgery. The cookie expires after 2
                        hours.
                    </li>
                    <li>
                        Session cookie: this is a unique cookie to identify a specific website visitor for the
                        duration of their visit (session). The cookie expires after 2 hours.
                    </li>
                    <li>
                        Analytics cookies: I use analytics cookies to collect information about the use of my
                        website. These analytics cookies provide me useful information to enhance your experience
                        and website visit. All of the analytics cookies are provided by a third-party because I have
                        integrated their services into my website.
                    </li>
                    <li>
                        _ga cookie: this is a Google Analytics cookie. I use it to measure and distinguish different
                        users on my website. That way, I can calculate the different visitors on my website. The
                        cookie expires after one week.
                    </li>
                    <li>
                        _gid cookie: this is a Google Analytics cookie. I use this cookie to measure and distinguish
                        different users on my website. That way, I can calculate the different users on my website.
                        The cookie expires after 24 hours.
                    </li>
                </ul>
                <p>
                    For further information about the Google Analytics cookies, please refer to the statements set
                    forth by Google on their
                    <x-front.legal-link
                        url="https://developers.google.com/analytics/devguides/collection/analyticsjs/cookie-usage"
                        text="website"
                        target="_blank"
                        after="."
                    />
                </p>
                <h3>How to control and adjust your cookie settings</h3>
                <ul>
                    <li>
                        Cookie settings within
                        <x-front.legal-link
                            url="https://support.google.com/accounts/answer/61416"
                            text="Google Chrome"
                            target="_blank"
                        />
                    </li>
                    <li>
                        Cookie settings within
                        <x-front.legal-link
                            url="https://support.apple.com/en-us/HT201265"
                            text="Apple's Safari"
                            target="_blank"
                        />
                    </li>
                    <li>
                        Cookie settings within
                        <x-front.legal-link
                            url="https://support.mozilla.org/en-US/kb/enable-and-disable-cookies-website-preferences"
                            text="Mozilla Firefox"
                            target="_blank"
                        />
                    </li>
                    <li>
                        Cookie settings within
                        <x-front.legal-link
                            url="https://help.opera.com/en/latest/web-preferences/#cookies"
                            text="Opera"
                            target="_blank"
                        />
                    </li>
                </ul>

                <h2>What are my rights?</h2>
                <h3>Guarantee of a legitimate and safe processing of the personal data.</h3>
                <p>
                    Randall processes your personal data fairly and lawfully. This includes the following
                    guarantees:
                </p>
                <ul>
                    <li>
                        Personal data will only be processed in accordance with specified and legitimate purposes of
                        this Privacy Statement.
                    </li>
                    <li>
                        Personal data are only processed to the extent that this is adequate, relevant and not
                        excessive.
                    </li>
                    <li>
                        Personal data are kept only as long as this is necessary to achieve the specified and
                        legitimate purposes in the Privacy Statement.
                    </li>
                </ul>
                <p>
                    The necessary technical and security measures were taken to reduce the risks of illegal access
                    to or processing of the personal data to a minimum. With intrusion into its computer systems,
                    Randall will immediately take all possible measures to limit the damage to a minimum.
                </p>

                <h3>The right to access/rectification/erasure of your personal data</h3>
                <p>
                    With proof of identity as a User, you have the right to receive from Randall an answer on
                    whether or not to process your personal data. When Randall processes your data, you
                    furthermore have the right to inspect the personal data collected. If you wish to use your right
                    to access, Randall will pursue the matter within one (1) month after receiving the request.
                    The application is done by sending an email to
                    <x-front.legal-link
                        url="mailto:{{ config('randallwilk.contact.email') }}"
                        :text="config('randallwilk.contact.email')"
                        after="."
                    />
                </p>
                <p>
                    Inaccurate or incomplete personal data can always be corrected. It is up to the User in the
                    first place to correct inaccuracies and incomplete information themself. You can exercise your
                    right to make corrections by providing an additional statement to Randall. Randall
                    will pursue the matter within one (1) month after receiving the additional report.
                </p>
                <p>
                    You have furthermore the right, without unreasonable delay to let us erase your personal data.
                    You can only make use of this right to be erased in the following cases:
                </p>
                <ul>
                    <li>
                        When your personal data are no longer necessary for the purposes for which they were
                        originally collected;
                    </li>
                    <li>
                        When your personal data were collected on the basis of a permission granted and there is no
                        other legal basis for the processing;
                    </li>
                    <li>
                        When an objection is made against the processing and there are no prevailing compelling
                        justified grounds for the processing to exist;
                    </li>
                    <li>
                        When the personal data were unlawfully processed;
                    </li>
                    <li>
                        When the personal data must be erased in accordance with a legal obligation.
                    </li>
                </ul>
                <p>Randall assesses the presence of one of the above-mentioned cases.</p>

                <h3>Right on limitation of/objection to the processing of your personal data</h3>
                <p>
                    The User has the right to obtain a limitation to the processing of your personal data:
                </p>
                <ul>
                    <li>
                        During the period that Randall needs to check the accuracy of the personal data, in the
                        event of a dispute;
                    </li>
                    <li>
                        When the data processing is unlawful and the User requests for a limitation of the
                        processing instead of erasing of personal data;
                    </li>
                    <li>
                        When Randall no longer needs the personal data for the processing purposes and the User
                        needs the personal data for a legal proceeding;
                    </li>
                    <li>
                        During the period that Randall needs to assess the presence of the basis for the
                        erasing of the personal data.
                    </li>
                </ul>
                <p>
                    The User has the right at all times to object to the processing of their personal data. Randall
                    then ceases the processing of your personal data, unless Randall has compelling justified
                    grounds for the processing of your personal data that can outweigh the User's right to object.
                </p>
                <p>
                    If the User wishes to exercise these rights, Randall will pursue the matter within one (1)
                    month after receiving the request. The application is done by sending an email to
                    <x-front.legal-link
                        url="mailto:{{ config('randallwilk.contact.email') }}"
                        :text="config('randallwilk.contact.email')"
                        after="."
                    />
                </p>

                <h3>The right to data transferability</h3>
                <p>
                    The User has the right to receive the personal data provided to Randall in a structured, common
                    and machine-readable form. In addition, the User has the right to transfer this personal data to
                    another controller when the processing of personal data solely rests on the permission obtained
                    from the User.
                </p>
                <p>
                    If the user wishes to exercise these rights, Randall will pursue the matter within one (1) month
                    after receiving the request. The application is done by sending an email to
                    <x-front.legal-link
                        url="mailto:{{ config('randallwilk.contact.email') }}"
                        :text="config('randallwilk.contact.email')"
                        after="."
                    />
                </p>

                <h3>Right on the withdrawal of my consent/right to complaint</h3>
                <p>
                    The User has the right at all times to withdraw their consent. The withdrawal of the consent
                    leaves the lawfulness of the processing on the basis of consent before the repeal of it without
                    prejudice.
                </p>
                <p>
                    If the User wishes to exercise these rights, Randall will pursue the matter within one (1) month
                    after receiving the request. The application is done by sending an email to
                    <x-front.legal-link
                        url="mailto:{{ config('randallwilk.contact.email') }}"
                        :text="config('randallwilk.contact.email')"
                        after="."
                    />
                </p>

                <h2>Children's Privacy</h2>
                <p>
                    My Service does not address anyone under the age of 13. I do not knowingly collect personally
                    identifiable information from anyone under the age of 13. If you are a parent or guardian and
                    you are aware that your child has provided me with Personal Data, please contact me. If I become
                    aware that I have collected Personal Data from anyone under the age of 13 without verification
                    of parental consent, I take steps to remove that information from my servers.
                </p>
                <p>
                    If I need to rely on consent as a legal basis for processing your information and your country
                    requires consent from a parent, I may require your parent's consent before I collect and use
                    that information.
                </p>

                <h2>Links to Other Websites</h2>
                <p>
                    The Site may contain links to other websites that are not operated by Randall. If you click on a
                    third party link, you will will be directed to that third party's site. I strongly advise you to
                    review the Privacy Policy of every site you visit.
                </p>
                <p>
                    I have no control over and assume no responsibility for the content, privacy policies or
                    practices of any third party sites or services.
                </p>

                <h3>Affiliate Program Participation</h3>
                <p>
                    The Site may engage in affiliate marketing, which is done by embedding tracking links into the Site.
                    If you click on a link for an affiliate partnership, a cookie will be placed on your browser to
                    track any sales for purposes of commissions.
                </p>
                <p>
                    Any affiliate link to a product have been picked by Randall. Randall is not paid to cover any
                    products linked on the Site. If you purchase the product, I may earn a small commission that helps
                    pay for my work. This does not cost you more; the price is the same as if you went directly to the
                    site.
                </p>
                <p>
                    If a manufacturer or retailer has paid for any editorial placement on the Site, it is noted clearly
                    with a Sponsored Content tag.
                </p>

                <h2>Effective Date</h2>
                <p>
                    This Privacy Policy became effective on: August 25, 2022.
                </p>
            </x-front.content-area>
        </section>
    </div>
</x-page>
