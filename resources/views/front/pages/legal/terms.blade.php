<x-page :title="__('front.terms.title')"
        :description="__('front.terms.description')"
>
    <x-front.page-banner>
        {{ __('front.terms.title') }}

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
                <h2>Ownership of Site; Agreement to Terms of Use</h2>
                <p>
                    These Terms and Conditions of Use (the "Terms of Use") apply to the website located at
                    <x-front.legal-link :url="url('/')" after="," />
                    and all associated sites linked to
                    <x-front.legal-link :url="url('/')" />
                    by Randall, (collectively, the "Site"). The
                    Site is the property of Randall Wilk ("Randall"). BY USING THE SITE, YOU AGREE TO THESE TERMS OF
                    USE; IF YOU DO NOT AGREE, DO NOT USE THE SITE.
                </p>
                <p>
                    Randall reserves the right, at his sole discretion, to change, modify, add ore remove portions
                    of these Terms of Use, at any time. It is your responsibility to check these Terms of Use
                    periodically for changes. Your continued use of the Site following the positing of changes will
                    mean that you accept and agree to the changes. As long as you comply with these Terms of Use,
                    Randall grants you a personal, non-exclusive, non-transferable, limited privilege to enter and
                    use the Site.
                </p>
                <p>
                    I shall make sure that the latest version of these Terms of Use shall always be available on
                    <x-front.legal-link :url="route('legal.terms')" after="." />
                </p>
                <p>
                    I may, in exceptional cases deviate from the general conditions, as far as these deviations are
                    established in writing and accepted by all parties. These deviations shall apply only to replace
                    or supplement clauses where they relate to and have no effect on the application or other
                    provisions of the general Terms of Use.
                </p>

                <h2>Definitions</h2>
                <p>
                    The following definitions apply to these Terms of Use and shall have the same meaning regardless
                    of whether they appear in singular or in plural:
                </p>
                <ul>
                    <li>
                        <span class="font-bold">Terms of Use:</span>
                        (also referred to as "Terms") mean these Terms of Use that form the entire agreement between
                        You and Randall regarding the use of the Service;
                    </li>
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
                        <span class="font-bold">Site:</span>
                        this website or any associated sites of
                        <x-front.legal-link :url="url('/')" after="." />
                    </li>
                </ul>

                <h2>Who am I?</h2>
                <p>
                    This website is an initiative of:
                </p>
                <address>
                    RANDALL WILK<br>
                    Email:
                    <x-front.legal-link url="mailto:{{ config('randallwilk.contact.email') }}" :text="config('randallwilk.contact.email')" />
                </address>
                <p>
                    Please feel free to contact me if you have further questions or comments.
                </p>

                <h2>Content</h2>
                <p>
                    All text, graphics, user interfaces, visual interfaces, photographs, trademarks, logos, sounds,
                    music, artwork and computer code (collectively, "Content"), including but not limited to the
                    design, structure, selection, coordination, expression, "look and feel" and arrangement of such
                    Content, contained on the Site is owned and controlled by Randall, and is protected by trade
                    dress, copyright, patent and trademark laws, and various other intellectual property rights and
                    unfair competition laws.
                </p>
                <p>
                    Except as expressly provided in these Terms of Use, no part of the Site and no Content may be
                    copied, reproduced, republished, uploaded, posted, publicly displayed, encoded, translated,
                    transmitted or distributed in any way (including "mirroring") to any other computer, server, Web
                    site or other medium for publication or distribution or for any commercial enterprise, without
                    Randall's express prior written consent.
                </p>
                <p>
                    You may use information on the Site (such as Docs) purposely made available by Randall for
                    downloading from the Site, provided that you (1) not remove any proprietary language in all
                    copies of such documents, (2) use such information only for your personal, non-commercial
                    information and do not copy or post such information on any networked computer or broadcast it
                    in any media, (3) make no modifications to any such information, and (4) not make any additional
                    representations or warranties relating to such documents.
                </p>

                <h2>Your Use of the Site</h2>
                <p>
                    You may not use any "deep-link", "page-scrape", "robot", "spider" or other automatic device,
                    program, algorithm or methodology, or any similar or equivalent manual process, to access,
                    acquire, copy or monitor any portion of the Site or any Content, or in any way reproduce or
                    circumvent the navigational structure or presentation of the Site or any Content, to obtain or
                    attempt to obtain any materials, documents or information through any means not purposely made
                    available through the Site. Randall reserves the right to bar any such activity.
                </p>
                <p>
                    You may not attempt to gain unauthorized access to any portion or feature of the Site, or any
                    other systems or networks connected to the Site or to any server owned by Randall, or to any of
                    the services offered on or through the Site, by hacking, password "mining" or any other
                    illegitimate means.
                </p>
                <p>
                    You may not probe, scan or test the vulnerability of the Site or any network connected to the
                    Site, nor breach the security or authentication measures on the Site or any network connected to
                    the Site. You may not reverse look-up, trace or seek to trace any information on any other user
                    of or visitor to the Site, or any other client of Randall, including any account not owned by
                    You, to its source, or exploit the Site or any service or information made available or offered
                    by or through the Site, in any way where the purpose is to reveal any information, including but
                    not limited to personal identification or information, other than your own information, as
                    provided for by the Site.
                </p>
                <p>
                    You agree that you will not take any action that imposes an unreasonable or disproportionately
                    large load on the infrastructure of the Site or Randall's systems or networks, or any systems or
                    networks connected to the Site or to Randall.
                </p>
                <p>
                    You agree not to use any device, software or routine to interfere or attempt to interfere with
                    the proper working of the Site or any transaction being conducted on the Site, or with any other
                    person's use of the Site.
                </p>
                <p>
                    You may not forge headers or otherwise manipulate identifiers in order to disguise the origin of
                    any message or transmittal you send to Randall on or through the Site or any service offered on
                    or through the Site. You may not pretend that you are, or that you represent, someone else, or
                    impersonate any other individual or entity.
                </p>
                <p>
                    You may not use the Site or any Content for any purpose that is unlawful or prohibited by these
                    Terms of Use, or to solicit the performance of any illegal activity or other activity which
                    infringes the rights of Randall or others.
                </p>

                <h2>Accounts, Passwords and Security</h2>
                <p>
                    Certain features or services offered on or through the Site may require you to open an account.
                    You are entirely responsible for maintaining the confidentiality of the information you hold for
                    your account, including your password, and for any and all activity that occurs under your
                    account as a result of your failing to keep this information secure and confidential. You agree
                    to notify Randall immediately of any unauthorized use of your account or password, or any other
                    breach of security. You may be held liable for losses incurred by Randall or any other user of
                    or visitor to the Site due to someone else using your account as a result of your failing to
                    keep your account information secure and confidential.
                </p>
                <p>
                    You may not use anyone else's email, password or account at any time without the express
                    permission and consent of the holder of that email, password or account. Randall cannot and will
                    not be liable for any loss or damage arising from your failure to comply with these obligations.
                </p>

                <h2>Privacy</h2>
                <p>
                    Randall's Privacy Policy applies to use of this Site, and its terms are made a part of these
                    Terms of Use by this reference. To view Randall's Privacy Policy,
                    <x-front.legal-link :href="route('legal.privacy')" after="." text="click here" />
                    Additionally, by using the Site, you acknowledge and agree that Internet transmissions are
                    never completely private or secure. You understand that any message or information you send to
                    the Site may be read or intercepted by others, even if there is a special notice that a
                    particular transmission (for example, credit card information) is encrypted.
                </p>

                <h2>Links to Other Sites and to this Site</h2>
                <p>
                    This Site may contain links to other independent third-party Web sites ("Linked Sites"). These
                    Linked Sites are provided solely as a convenience to my visitors. Such Linked Sites are not
                    under Randall's control, and Randall is not responsible for and does not endorse the content of
                    such Linked Sites, including any information or materials contained on such Linked Sites. You
                    will need to make your own independent judgement regarding your interaction with these Linked
                    Sites.
                </p>

                <h2>Disclaimers</h2>
                <p>
                    RANDALL DOES NOT PROMISE THAT THE SITE OR ANY CONTENT, SERVICE OR FEATURE OF THE SITE WILL BE
                    ERROR-FREE OR UNINTERRUPTED, OR THAT ANY DEFECTS WILL BE CORRECTED, OR THAT YOUR USE OF THE SITE
                    WILL PROVIDE SPECIFIC RESULTS. THE SITE AND ITS CONTENT ARE DELIVERED ON AN "AS-IS" AND
                    "AS-AVAILABLE" BASIS. ALL INFORMATION PROVIDED ON THE SITE IS SUBJECT TO CHANGE WITHOUT NOTICE.
                    RANDALL CANNOT ENSURE THAT ANY FILES OR OTHER DATA YOU DOWNLOAD FROM THE SITE WILL BE FREE OF
                    VIRUSES OR CONTAMINATION OR DESTRUCTIVE FEATURES. RANDALL DISCLAIMS ALL WARRANTIES, EXPRESS OR
                    IMPLIED, INCLUDING ANY WARRANTIES OF ACCURACY, NON-INFRINGEMENT, MERCHANTABILITY AND FITNESS FOR
                    A PARTICULAR PURPOSE. RANDALL DISCLAIMS ANY AND ALL LIABILITY FOR THE ACTS, OMISSIONS AND
                    CONDUCT OF ANY THIRD PARTIES IN CONNECTION WITH OR RELATED TO YOUR USE OF THE SITE AND/OR ANY
                    SERVICES. YOU ASSUME TOTAL RESPONSIBILITY FOR YOUR USE OF THE SITE AND ANY LINKED SITES. YOUR
                    SOLE REMEDY AGAINST RANDALL FOR DISSATISFACTION WITH THE SITE OR ANY CONTENT IS TO STOP USING
                    THE SITE OR ANY SUCH CONTENT. THIS LIMITATION OF RELIEF IS A PART OF THE BARGAIN BETWEEN THE
                    PARTIES.
                </p>
                <p>
                    The above disclaimer applies to any damages, liability or injuries caused by any failure of
                    performance, error, omission, interruption, deletion, defect, delay in operation or
                    transmission, computer virus, communication line failure, theft or destruction of or
                    unauthorized access to, alteration of, or use, whether for breach of contract, tort, negligence
                    or any other cause of action.
                </p>
                <p>
                    Randall reserves the right to do any of the following, at any time, without notice: (1) to
                    modify, suspend or terminate operation of or access to the Site, or any portion of the Site, for
                    any reason; (2) to modify or change the Site, or any portion of the Site, and any applicable
                    policies and terms; and (3) to interrupt the operation of the Site, or any portion of the Site,
                    as necessary to perform routine or non-routine maintenance, error correction, or other changes.
                </p>

                <h2>Third-Party Licenses</h2>
                <p>
                    Specific components from any of my open-source packages may require third-party licenses or MIT
                    licenses and may be subject to other terms and conditions apart from these Terms of Use.
                </p>
                <p>
                    You shall be required to obtain for yourself all licenses for third-party software not included
                    within any package documented on the Site. Randall does not assume any responsibility in case of
                    infringement of such third-party license Terms of Use by You.
                </p>

                <h2>Limitation of Liability</h2>
                <p>
                    Except where prohibited by law, in no event will Randall be liable to you for any indirect,
                    consequential, exemplary, incidental or punitive damages, including lost profits, even if
                    Randall has been advised of the possibility of such damages.
                </p>

                <h2>Indemnity</h2>
                <p>
                    You agree to indemnify and hold Randall harmless from any demands, loss, liability, claims or
                    expenses (including attorneys' fees), made against Randall by any third party due to or arising
                    out of or in connection with your use of the Site.
                </p>

                <h2>Violation of These Terms of Use</h2>
                <p>
                    Randall may disclose any information he has about you (including your identity) if he determines
                    that such disclosure is necessary in connection with any investigation or complaint regarding
                    your use of Site, or to identify, contact or bring legal action against someone who may be
                    causing injury to or interference with (either intentionally or unintentionally) Randall's
                    rights or property, or the rights or property of visitors to or users of the Site, including
                    Randall's clients. Randall reserves the right at all times to disclose any information that
                    Randall deems necessary to comply with any applicable law, regulation, legal process or
                    governmental request. Randall also may disclose your information when Randall determines that
                    applicable law requires or permits such disclosure, including exchanging information with other
                    companies and organizations for fraud protection purposes.
                </p>
                <p>
                    You acknowledge and agree that Randall may preserve any transmittal or communication by you with
                    Randall through the Site or any service offered on or through the Site, and may also disclose
                    such data if required to do so by law or Randall determines that such preservation or disclosure
                    is reasonably necessary to (1) comply with legal process, (2) enforce these Terms of Use, (3)
                    response to claims that any such data violates the rights of others, or (4) protect the rights
                    or personal safety of Randall, users of or visitors to the Site, and the public.
                </p>
                <p>
                    You agree that Randall may, in his sole discretion and without prior notice, terminate your
                    access to the Site and/or block your future access to the Site if he determines that you have
                    violated these Terms of Use or other agreements or guidelines which may be associated with y our
                    use of the Site. You also agree that any violation by you of these Terms of Use will constitute
                    an unlawful and unfair business practice, and you consent to Randall obtaining any injunctive or
                    equitable relief that Randall deems necessary or appropriate in such circumstances. These
                    remedies are in addition to any other remedies that Randall may have at law or in equity.
                </p>
                <p>
                    You agree that Randall may, in his sole discretion and without prior notice, terminate your
                    access to the Site, for cause, which includes (but is not limited to) (1) requests by law
                    enforcement or other government agencies, (2) a request by you (self-initiated account
                    deletions), (3) discontinuance or material modification of the Site or any service offered on or
                    through the Site, or (4) unexpected technical issues or problems.
                </p>
                <p>
                    If Randall does take any legal action against you as a result of your violation of these Terms
                    of Use, Randall will be entitled to recover from you, and you agree to pay, all reasonable
                    attorneys' fees and costs of such action, in addition to any other relief granted to Randall.
                    You agree that Randall will not be liable to you or to any third party for termination of your
                    access to the Site as a result of any violation of these Terms of Use.
                </p>

                <h2>Governing Law; Dispute Resolution</h2>
                <p>
                    You agree that all matters relating to your access to or use of the Site, including all
                    disputes, will be governed by the laws of the United States and by the laws of the State of
                    Wisconsin without regard to its conflicts of laws provisions. You agree to the personal
                    jurisdiction by and venue in the state and federal courts in Marathon County, Wisconsin, and
                    waive any objection to such jurisdiction or venue. The preceding provision regarding venue does
                    not apply if you are a consumer based in the European Union. If you are a consumer based in the
                    European Union, you may take a claim in the courts of the country where you reside. Any claim
                    under these Terms of Use must be brought within one (1) year after the cause of action arises,
                    or such claim or cause is barred. No recovery may be sought or received for damages other than
                    out-of-pocket expenses, except that the prevailing party will be entitled to costs and
                    attorneys' fees. In the event of any controversy or dispute between Randall and you arising out
                    of or in connection with your use of the Site, the parties shall attempt, promptly and in good
                    faith, to resolve such dispute. If I am unable to resolve any such dispute within a reasonable
                    time (not to exceed thirty (30) days), then either party may submit such controversy or dispute
                    to mediation. If the dispute cannot be resolved through mediation, then the parties shall be
                    free to pursue any right or remedy available to them under applicable law.
                </p>

                <h2>Void Where Prohibited</h2>
                <p>
                    Randall administers and operates the
                    <x-front.legal-link :url="url('/')" />
                    Site from his location in Wausau, Wisconsin USA; other sites from Randall may be administered
                    and operated from various locations inside or outside the United States. Although the Site is
                    accessible worldwide, not all features or services discussed, referenced, provided or offered
                    through or on the Site are available to all persons or in all geographic locations, or
                    appropriate or available for use outside the United States. Randall reserves the right to limit,
                    in his sole discretion, the provision and quantity of any feature or service to any person or
                    geographic area. Any offer for any feature or service made on the Site is void where prohibited.
                    If you choose to access the Site from outside the United States, you do so on your own
                    initiative and you are solely responsible for complying with applicable local laws.
                </p>

                <h2>Miscellaneous</h2>
                <p>
                    You may not use or export or re-export any Content or any copy or adaptation of such Content, or
                    any product or service offered on the Site, in violation of any applicable laws or regulations,
                    including without limitation United States export laws and regulations.
                </p>
                <p>
                    If any of the provisions of these Terms of Use are held by a court or other tribunal of
                    competent jurisdiction to be void or unenforceable, such provisions shall be limited or
                    eliminated to the minimum extent necessary and replaced with a valid provision that best
                    embodies the intent of these Terms of Use, so that these Terms of Use shall remain in full force
                    and effect. These Terms of Use constitute the entire agreement between you and Randall with
                    regard to your use of the Site, and any and all other written or oral agreements or
                    understandings previously existing between you and Randall with respect to such use are hereby
                    superseded and cancelled. Randall will not accept any counter-offers to these Terms of Use, and
                    all such offers are hereby categorically rejected. Randall's failure to insist on or enforce
                    strict performance of these Terms of Use shall not be construed as a waiver by Randall of any
                    provision or any right he has to enforce these Terms of Use, nor shall any course of conduct
                    between Randall and you or any other party be deemed to modify any provision of these Terms of
                    Use. These Terms of Use shall not be interpreted or construed to confer any rights or remedies
                    on any third parties.
                </p>

                <h2>Feedback and Information</h2>
                <p>
                    Any feedback you provide at this Site shall be deemed to be non-confidential. Randall shall be
                    free to use such information on an unrestricted basis.
                </p>

                <p class="italic">
                    The information contained in this website is subject to change without notice.
                    <br>Copyright &copy; 2015 - {{ now()->year }} Randall Wilk. All rights reserved.
                </p>

                <p>
                    Updated by Randall Wilk on Aug. 25, 2022
                </p>
            </x-front.content-area>
        </section>
    </div>
</x-page>
