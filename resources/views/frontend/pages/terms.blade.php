@extends('layouts.frontend-app')
@section('title', 'Terms & Conditions')

@push('meta')
    <meta name="description" content="{{ config('randallwilk.default_page_description') }}">
    <meta property="og:type" content="article">
    <meta property="og:description" content="{{ config('randallwilk.default_page_description') }}">
@endpush

@section('content')
    @php
        $breadcrumbs = [
            ['url' => '#', 'display' => 'Terms & Conditions']
        ];
    @endphp

    @component('frontend.components.page-header', ['breadcrumbs' => $breadcrumbs])
        @slot('title')
            Terms & Conditions
        @endslot
    @endcomponent

    <div class="container py-4">
        <div class="row">
            <div class="col">
                <p class="lead">
                    The following terms and conditions apply to all website development services provided
                    by Randall Wilk to the client.
                </p>

                <ol class="terms">
                    <li class="terms-heading">
                        Acceptance

                        <p>
                            It is not necessary for any client to have signed an acceptance of these terms and
                            conditions for them to apply. If a client accepts a quote then the client will
                            be deemed to have satisfied themselves as to the terms applying and have accepted
                            these terms and conditions in full.
                        </p>
                        <p>
                            Please read these terms and conditions carefully. Any purchase or use of my services implies
                            that you have read and accepted my terms and conditions. All contracts that Randall Wilk
                            may enter into from time to time [for the provision of Randall Wilk's services] shall
                            be governed by these Terms and Conditions, and Randall Wilk will ask the client for
                            the client's express written acceptance of these Terms and Conditions before providing
                            any website development services to the client.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Fees and Deposits

                        <p>
                            A 50% deposit of the total fee payable under my proposal is due immediately upon you constructing
                            me to proceed with the website design and development work. The remaining 50% shall become due
                            when the work is completed to your reasonable satisfaction but subject to the terms of
                            the "approval of work" and "rejected work" clauses. I reserve the right not to
                            commence any work until the deposit has been paid in full.
                        </p>
                        <p>
                            The 50% deposit is only refundable if I have not fulfilled my obligations to deliver
                            the work required under the agreement. The deposit is not refundable if the development work
                            has been started and you terminate the contract through not fault of mine.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Supply of Materials

                        <p>
                            You must supply all materials and information required by me to complete the work in
                            accordance with any agreed specification. Such materials may include, but are not limited
                            to, photographs, written copy, logos and other printed material. Where there is any delay
                            in supplying materials to me which leads in a delay in the completion of work, I have the right
                            to extend any previously agreed deadlines by a reasonable amount.
                        </p>
                        <p>
                            Where you fail to supply materials, and that prevents the progress of the work, I have
                            the right to invoice you for any part or parts of the work already completed.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Variations

                        <p>
                            I am pleased to offer you the opportunity to make revisions to the design. However, I have the
                            right to limit the number of design proposals to a reasonable amount and may charge for additional
                            designs if you make a change to the original design specification.
                        </p>
                        <p>
                            My website development phase is flexible and allows certain variations to the original
                            specification. However any major deviation from the specification will be charge
                            at the rate of $50.00 per hour.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Project Delays and client Liability

                        <p>
                            Any time frames or estimates that I give are contingent upon your full co-operation and
                            complete and final content in photography for the work pages. During development there
                            is a certain amount of feedback required in order to progress to subsequent phases. It
                            is required that a single point of contact be appointed from your side and be made
                            available on a daily basis in order to expedite the feedback process.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Approval of Work

                        <p>
                            On completion of the work you will be notified and have the opportunity to review it.
                            You must notify me in writing of any unsatisfactory points within 7 days of such notification.
                            Any of the work which has not been reported in writing to us as unsatisfactory within the
                            7-day review period will be deemed to have been approved. Once approved, or deemed approved,
                            work cannot subsequently be rejected and the contract will be deemed to have been completed
                            and the 50% balance of the project price will become due.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Rejected Work

                        <p>
                            If you reject any of my work within the 7-day review period, or not approve subsequent work
                            performed by me to remedy any points recorded as being unsatisfactory, and I, acting reasonably,
                            consider that you have been unreasonable in any rejection of the work, I can elect to treat this
                            contract as at an end and take measures to recover payment for the completed work.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Payment

                        <p>
                            Upon completion of the 7-day review period, I will invoice you for the
                            remaining 50% balance of the project.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Warranty By You As To Ownership of Intellectual Property Rights

                        <p>
                            You must obtain all necessary permissions and authorities in respect of hte use of all copy,
                            graphic images, registered company logos, names and trade marks, or any other material that you
                            supply to me to include in your website or web applications.
                        </p>
                        <p>
                            You must indemnify me and hold me harmless from any claims or legal actions  to the content of your
                            website.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Licensing

                        <p>
                            Once you have paid me in full for my work I grant to you a license to use the website and its
                            related software and contents for the life of the website.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Search Engines

                        <p>
                            I do not guarantee any specific position in search engine results for your website.
                            I perform basic search engine optimization according to best practice.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Consequential Loss

                        <p>
                            I shall not be liable for any loss or damage which you may suffer which is in any way
                            attributable to any delay in performance or completion of our contract, however that
                            any delay arises.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Disclaimer

                        <p>
                            To the full extent permitted by law, all terms, conditions, warranties, undertakings, inducements
                            or representations whether express, implied, statutory or otherwise (other than the express provisions
                            of these terms and conditions) relating in any way to the services I provided to you are excluded.
                            Without limiting the above, to the extent permitted by law, any liability of Randall Wilk under any
                            term, condition, warranty or representation that by law cannot be excluded is, where permitted by
                            law, limited at my option to the replacement, re-repair or re-supply of the services or the payment
                            of hte cost of the services that I was contracted to perform.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Subcontracting

                        <p>
                            I reserve the right to subcontract any services that I have agreed to perform as I see fit.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Non-Disclosure

                        <p>
                            I (and any subcontractors I engage) agree that I will not at any time disclose any of your confidential
                            information to any third party.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Additional Expenses

                        <p>
                            You agree to reimburse me for any requested expenses which do not form part of my proposal including
                            but not limited to the purchase of templates, third party software, stock photographs, fonts,
                            domain name registration, web hosting or comparable expenses.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Design Credit

                        <p>
                            A link to randallwilk.com will appear in either small type or by a small graphic
                            at the bottom of the client's website. If a graphic is used, it will be designed
                            to fit in with the overall site design. If a client requests that the design credit
                            be removed, a nominal fee of 10% of the total development charges will be applied.
                        </p>
                        <p>
                            The client also agrees that the website developed for the client
                            may be presented in Randall Wilk's portfolio. Only front-facing websites
                            will ever be displayed in the portfolio. Business applications with no web site
                            will never be displayed in the portfolio.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Backups

                        <p>
                            You are responsible for maintaining your own backups with respect to your website and I will
                            not be liable for restoring any client data or client websites except to the extent that such
                            data loss arises out of a negligent act or omission by me.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Ownership of Domain Names and Web Hosting

                        <p>
                            I will supply to you account credentials for domain name registration and/or web hosting that
                            I purchased on your behalf when you reimburse mem for any expenses that I have incurred.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Post-Placement Alterations

                        <p>
                            Randall Wilk cannot accept responsibility for any alterations caused by a third party
                            occurring to the client's pages once installed. Such alterations include, but are
                            not limited to additions, modifications or deletions.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Governing Law

                        <p>
                            The agreement constituted by these terms and conditions and any proposal
                            will be construed according to and is governed by the laws of Wisconsin.
                            You and Randall Wilk submit to the non-exclusive jurisdiction of the courts
                            in and of Wisconsin in relation to any dispute arising under these terms
                            and conditions or in relation to any services I perform for you.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Cross Browser Compatibility

                        <p>
                            By using current versions of well supported supported content management systems such as
                            "WordPress" or frameworks such as "Laravel", I endeavour to ensure that the web sites I create
                            are compatible with all current modern web browsers such as the most recent versions of
                            Firefox, Google Chrome, Microsoft Edge and Safari. Since it is no longer supported, I cannot
                            guarantee compatibility with any version of Internet Explorer. Third party extensions, where used,
                            may not have the same level of support for all browsers. Where appropriate I will substitute
                            alternative extensions or implement other solutions, on a best effort basis, where any
                            incompatibilities are found.
                        </p>
                    </li>
                    <li class="terms-heading">
                        E-Commerce

                        <p>
                            You are responsible for complying with all relevant laws relating to e-commerce, and to the full
                            extent permitted by law will hold harmless, protect and defend and indemnify Randall Wilk
                            and any subcontractors from any claim, penalty, tax, tariff loss or damage arising from your or
                            your clients' use of Internet electronic commerce.
                        </p>
                    </li>
                    <li class="terms-heading">
                        Contact Me
                        <ul class="list">
                            <li>{{ config('randallwilk.contact_name') }}</li>
                            <li>{{ config('randallwilk.contact_email') }}</li>
                        </ul>
                    </li>
                </ol>
            </div>
        </div>
    </div>
@endsection