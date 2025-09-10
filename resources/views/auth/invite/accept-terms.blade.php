@extends('layouts.guest')

@section('title', 'Welcome | Step 3: Terms & Conditions')

@section('content')

    <!-- Fullscreen Centered Container -->
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
        <!-- Registration Container -->
        <div class="w-full max-w-3xl bg-white rounded-xl shadow-lg overflow-hidden">

            <!-- Progress Indicator -->
            <div class="bg-[#f8f9fb] p-6 border-b border-[#e4e7ec]">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="relative w-12 h-12">
                            <div class="w-12 h-12 rounded-full border-4 border-[#7e56d8] border-r-transparent animate-spin"></div>
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-sm font-medium">
                                3/3
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold text-[#0f1728]">Terms & Conditions</h2>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6">
                <form method="POST" action="{{ route('invitations.accept.terms.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Scrollable Terms -->
                    <div class="flex flex-col gap-2">
                        <label for="terms-scroll" class="text-[#344053] text-sm font-medium">Please review our Terms & Conditions</label>
                        <div id="terms-scroll" class="h-64 overflow-y-scroll border border-[#e4e7ec] rounded p-4 text-sm text-[#475466] bg-[#f9fafb]">
                            <article class="terms" aria-labelledby="deckcheck-beta-terms">
  <h1 id="deckcheck-beta-terms">DeckCheck Beta Terms &amp; Conditions</h1>

  <p><strong>Effective upon your acceptance.</strong> These Beta Terms &amp; Conditions (“<strong>Terms</strong>”) govern access to and use of the <strong>DeckCheck Beta</strong> pre-release service (“<strong>Beta Services</strong>”) offered by <strong>DeckCheck Technologies, LLC</strong>, a Connecticut limited liability company with a principal place of business at <strong>3 Elmcrest Terrace, Norwalk, CT 06850</strong> (“<strong>DeckCheck</strong>,” “<strong>we</strong>,” “<strong>us</strong>,” or “<strong>our</strong>”). By creating an account or using the Beta Services, you (“<strong>you</strong>” or “<strong>User</strong>”) agree to these Terms and our Privacy Policy at <a href="https://deckcheck.app/privacy" rel="noopener noreferrer">deckcheck.app/privacy</a>.</p>

  <h2 id="section-1">1. Nature of the Beta; Agreement Formation</h2>
  <p><strong>1.1 Pre-Release Status.</strong> The Beta Services are experimental, pre-release, may change frequently, and may contain defects. They are provided <strong>for evaluation only</strong> and <strong>not for safety-critical or mission-critical use</strong>.</p>
  <p><strong>1.2 Click-Accept.</strong> You agree to these Terms by clicking “I agree,” creating an account, or using the Beta Services.</p>
  <p><strong>1.3 Order of Precedence.</strong> If there is a conflict, these Terms control over our Privacy Policy. No separate DPA is provided during Beta.</p>

  <h2 id="section-2">2. Definitions</h2>
  <p><strong>“Customer Data”</strong> means information you or your Authorized Users submit to or through the Beta Services, including vessel data, equipment data, maintenance records, photos, logs, and crew information. <strong>“Confidential Information”</strong> means non-public information disclosed by one party to the other in connection with the Beta, including features, roadmaps, UI/UX, performance data, and pricing. <strong>“Competitor”</strong> means any person or entity that develops, markets, or provides products or services that are substantially similar to, functionally equivalent to, or could reasonably substitute for the Beta Services. <strong>“Feedback”</strong> means suggestions, ideas, or reports you provide.</p>

  <h2 id="section-3">3. Eligibility; Accounts; Authorized Users</h2>
  <p><strong>3.1 Who May Use.</strong> The Beta is available to businesses and individuals aged <strong>18+</strong> with capacity to contract. If you accept on behalf of an entity, you represent you have authority to bind that entity.</p>
  <p><strong>3.2 Authorized Users.</strong> Access may be provisioned only to <strong>named, Authorized Users</strong> under your account. You are responsible for all activity under your account and for maintaining the confidentiality of credentials.</p>

  <h2 id="section-4">4. Competitive Safeguards; Conflicts of Interest (Key)</h2>
  <p><strong>4.1 COI Representation.</strong> You represent and warrant that you are <strong>not a Competitor</strong> and are <strong>not acting on behalf of a Competitor</strong> and that your participation does not create a conflict of interest. You will promptly notify DeckCheck if this status changes.</p>
  <p><strong>4.2 No Competitive Use.</strong> You will not access the Beta Services for purposes of building or improving a competing or adjacent product, nor to benchmark, publish performance tests, or otherwise analyze the Beta for competitive intelligence.</p>
  <p><strong>4.3 No Workarounds.</strong> You will not circumvent technical controls, scrape, or attempt to discover the source code or underlying methodologies.</p>

  <h2 id="section-5">5. License; Acceptable Use</h2>
  <p><strong>5.1 License.</strong> Subject to these Terms, DeckCheck grants you a limited, revocable, non-exclusive, non-transferable license to access and use the Beta Services solely for your internal evaluation during the Beta.</p>
  <p><strong>5.2 Restrictions.</strong> You will not (a) copy, modify, reverse engineer, or create derivative works of the Beta; (b) sublicense or share credentials; (c) interfere with or disrupt the Beta; or (d) upload illegal content or content that infringes third-party rights.</p>
  <p><strong>5.3 Safety &amp; Maritime Notice.</strong> The Beta Services <strong>are not a substitute for ISM/SMS compliance</strong> and <strong>make no representation as such</strong>. The vessel remains solely responsible for compliance and official records and decisions.</p>

  <h2 id="section-6">6. Scope of Beta; Changes</h2>
  <p>The Beta may include any DeckCheck modules or features and is <strong>broad in scope</strong> and <strong>subject to frequent change</strong>. Features may be added, removed, or altered at any time without notice. No SLA, uptime, or support commitments apply during Beta.</p>

  <h2 id="section-7">7. Confidentiality &amp; Non-Disclosure (Key)</h2>
  <p><strong>7.1 Obligation.</strong> You will keep all Confidential Information <strong>strictly confidential</strong>, use it only for evaluating the Beta, and not disclose it to any third party. <strong>No screenshots, demos, public posts, or disclosures are permitted</strong> during the Beta.</p>
  <p><strong>7.2 Exclusions.</strong> Confidentiality does not apply to information that is (a) publicly available without breach; (b) independently developed without use of Confidential Information; or (c) rightfully received from a third party without confidentiality obligations.</p>
  <p><strong>7.3 Duration.</strong> The confidentiality obligation lasts through the Beta and for <strong>three (3) years</strong> thereafter; trade secrets remain protected as long as they qualify as trade secrets.</p>
  <p><strong>7.4 Remedies.</strong> You acknowledge that breach may cause irreparable harm and agree DeckCheck may seek injunctive relief in addition to other remedies.</p>

  <h2 id="section-8">8. Data; Privacy; Telemetry</h2>
  <p><strong>8.1 Ownership.</strong> You retain all right, title, and interest in and to Customer Data. DeckCheck retains all right, title, and interest in and to the Beta Services and related IP.</p>
  <p><strong>8.2 Processing License.</strong> You grant DeckCheck a license to host, process, transmit, and display Customer Data <strong>solely</strong> to provide, secure, support, and improve the Beta Services.</p>
  <p><strong>8.3 Privacy.</strong> Processing is subject to <a href="https://deckcheck.app/privacy" rel="noopener noreferrer">deckcheck.app/privacy</a>. <strong>No DPA is offered during Beta.</strong></p>
  <p><strong>8.4 Telemetry.</strong> You consent to our collection of <strong>usage analytics, diagnostics, and performance logs</strong> (which may include device, configuration, and interaction metadata) to operate and improve the Beta.</p>
  <p><strong>8.5 Hosting &amp; Geography.</strong> The Beta is hosted on <strong>AWS / Laravel Cloud</strong>. Participation is <strong>global</strong>, subject to export/sanctions laws.</p>

  <h2 id="section-9">9. Security; Incidents</h2>
  <p><strong>9.1 Safeguards.</strong> DeckCheck implements commercially reasonable security measures appropriate for a pre-release service; certifications (e.g., SOC 2/ISO) are <strong>not</strong> represented during Beta.</p>
  <p><strong>9.2 Incident Notice.</strong> If we confirm a security incident affecting Customer Data, we will notify you <strong>without undue delay</strong> (target within <strong>72 hours</strong>) and share available remediation information. Notification is not an admission of fault.</p>

  <h2 id="section-10">10. Data Loss/Leakage Risk; Release of Liability (Key)</h2>
  <p><strong>10.1 Beta Risks.</strong> You acknowledge that the Beta may contain defects that could result in <strong>data corruption, deletion, or unauthorized disclosure</strong>.</p>
  <p><strong>10.2 Your Backups.</strong> You are solely responsible for maintaining <strong>independent backups</strong> and records of critical vessel and crew data.</p>
  <p><strong>10.3 Release.</strong> <strong>To the maximum extent permitted by law</strong>, you <strong>release and hold harmless</strong> DeckCheck from and against any claims, liabilities, damages, losses, costs, or expenses arising out of or related to <strong>accidental</strong> deletion, corruption, or unauthorized disclosure of Customer Data <strong>caused by Beta defects or third-party outages</strong>, except to the extent caused by DeckCheck’s <strong>willful misconduct or gross negligence</strong>.</p>

  <h2 id="section-11">11. Data Export; Retention; Deletion</h2>
  <p><strong>11.1 Export.</strong> DeckCheck may offer data export options during Beta, but format, completeness, and availability are <strong>not guaranteed</strong>.</p>
  <p><strong>11.2 Retention &amp; Deletion.</strong> Upon Beta end or your account termination, DeckCheck may retain Customer Data for <strong>30 days</strong> and then delete it from active systems.</p>
  <p><strong>11.3 Deletion Requests.</strong> Submit deletion requests to <a href="mailto:admin@deckcheck.app">admin@deckcheck.app</a>. Deletion may be delayed for legal holds or backups.</p>

  <h2 id="section-12">12. Feedback</h2>
  <p>You grant DeckCheck a <strong>perpetual, irrevocable, worldwide, royalty-free</strong> license to use and incorporate Feedback without restriction. Do not include third-party confidential information in Feedback.</p>

  <h2 id="section-13">13. Third-Party Services</h2>
  <p>The Beta may interoperate with or depend on third-party services (e.g., hosting, authentication, email, analytics, AI). Those are provided “as is.” DeckCheck is not responsible for third-party availability or data handling.</p>

  <h2 id="section-14">14. Fees</h2>
  <p>Participation for initial beta users is <strong>free</strong>. No refunds apply unless required by law.</p>

  <h2 id="section-15">15. Warranties &amp; Disclaimers</h2>
  <p>THE BETA SERVICES ARE PROVIDED <strong>“AS IS” AND “AS AVAILABLE.”</strong> DECKCHECK DISCLAIMS <strong>ALL WARRANTIES</strong>, EXPRESS OR IMPLIED, INCLUDING <strong>MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, NON-INFRINGEMENT, AND ACCURACY OF OUTPUTS</strong>. YOU ARE RESPONSIBLE FOR VERIFYING ANY OUTPUTS AND FOR ALL OPERATIONAL, SAFETY, AND COMPLIANCE DECISIONS.</p>

  <h2 id="section-16">16. Indemnification by User</h2>
  <p>You will defend, indemnify, and hold DeckCheck harmless from claims, damages, and costs (including reasonable attorneys’ fees) arising out of: (a) your Customer Data; (b) your breach of these Terms, applicable law, or rights of others; or (c) use of the Beta with unsafe or unlawful systems.</p>

  <h2 id="section-17">17. Limitation of Liability</h2>
  <p><strong>17.1 Exclusion of Damages.</strong> TO THE FULLEST EXTENT PERMITTED BY LAW, NEITHER PARTY IS LIABLE FOR <strong>INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, PUNITIVE, OR EXEMPLARY DAMAGES</strong>, OR FOR <strong>LOST PROFITS, REVENUE, GOODWILL, OR DATA</strong>, EVEN IF ADVISED OF THE POSSIBILITY.</p>
  <p><strong>17.2 Overall Cap.</strong> DECKCHECK’S <strong>TOTAL AGGREGATE LIABILITY</strong> ARISING OUT OF OR RELATED TO THE BETA WILL NOT EXCEED THE <strong>GREATER OF (A) ONE HUNDRED DOLLARS (US $100) OR (B) THE AMOUNTS PAID BY YOU TO DECKCHECK FOR THE BETA IN THE TWELVE (12) MONTHS BEFORE THE EVENT GIVING RISE TO LIABILITY; PROVIDED THAT IF THE BETA IS FREE, THE CAP IS US $0.</strong></p>
  <p><strong>17.3 Essential Purpose.</strong> The limitations in this Section apply regardless of the legal theory and even if a remedy fails of its essential purpose. Nothing limits liability to the extent it cannot be limited under applicable law.</p>

  <h2 id="section-18">18. Publicity</h2>
  <p>No publicity, logos, testimonials, or case studies may be used by either party concerning the other <strong>without prior written consent</strong>.</p>

  <h2 id="section-19">19. Suspension; Term; Termination</h2>
  <p><strong>19.1 Suspension.</strong> DeckCheck may <strong>suspend or restrict</strong> access <strong>in its sole discretion</strong>, including for AUP violations, competitive concerns, security risks, or operational reasons.</p>
  <p><strong>19.2 Term &amp; Termination.</strong> These Terms take effect upon acceptance and continue until the Beta ends or either party terminates at will. Upon termination, access ceases and Section 11 governs data handling.</p>
  <p><strong>19.3 Survival.</strong> Sections 4–5, 7–8, 10–12, 15–18, and 21–23 survive termination.</p>

  <h2 id="section-20">20. Export; Sanctions; Compliance</h2>
  <p>You will comply with all applicable laws, including export controls and economic sanctions. You will not use the Beta where prohibited and will not submit illegal content.</p>

  <h2 id="section-21">21. Dispute Resolution; Arbitration (Connecticut)</h2>
  <p><strong>21.1 Governing Law.</strong> These Terms are governed by the laws of the <strong>State of Connecticut</strong>, excluding its conflicts of laws rules.</p>
  <p><strong>21.2 Informal Resolution.</strong> Before filing a claim, the parties will attempt to resolve disputes informally for <strong>30 days</strong> after written notice.</p>
  <p><strong>21.3 Binding Arbitration.</strong> Except for claims that may be brought in small claims court or seeking injunctive relief for confidentiality/IP breaches, <strong>any dispute</strong> arising out of or relating to these Terms or the Beta will be <strong>resolved by binding arbitration</strong> administered by the <strong>American Arbitration Association (AAA)</strong> under its <strong>applicable rules</strong> (AAA <strong>Commercial Rules</strong> for business users; AAA <strong>Consumer Rules</strong> for individuals), by a <strong>single arbitrator</strong>, seated in <strong>Stamford, Connecticut</strong>. The arbitration will be confidential. Judgment may be entered in any court of competent jurisdiction.</p>
  <p><strong>21.4 Venue for Non-Arbitrable Matters.</strong> For claims not subject to arbitration (including injunctive relief), the <strong>state and federal courts located in Stamford/Norwalk, Connecticut</strong> will have exclusive jurisdiction and venue, and the parties consent to personal jurisdiction there.</p>
  <p><strong>21.5 Class Action &amp; Jury Trial Waiver.</strong> <strong>YOU AND DECKCHECK WAIVE ANY RIGHT TO PARTICIPATE IN A CLASS OR REPRESENTATIVE ACTION OR PROCEEDING, AND WAIVE THE RIGHT TO A JURY TRIAL</strong>, to the extent permitted by law.</p>

  <h2 id="section-22">22. Assignment</h2>
  <p>You may not assign these Terms or your rights hereunder without DeckCheck’s prior written consent. DeckCheck may assign these Terms (in whole or part) in connection with a merger, acquisition, corporate reorganization, or sale of assets.</p>

  <h2 id="section-23">23. Force Majeure</h2>
  <p>Neither party is liable for delays or failures caused by events beyond reasonable control, including acts of God, Internet or cloud provider failures, power outages, war, labor actions, or government actions.</p>

  <h2 id="section-24">24. Notices</h2>
  <p>Legal notices must be sent by email to <a href="mailto:admin@deckcheck.app">admin@deckcheck.app</a> and, if to you, to the email associated with your account. Notices are deemed given when sent, except for notices of termination or alleged breach, which require confirmation of receipt.</p>

  <h2 id="section-25">25. Modifications to Terms</h2>
  <p>We may update these Terms from time to time. Material changes will be notified by email or in-app. Your continued use after the effective date constitutes acceptance of the revised Terms.</p>

  <h2 id="section-26">26. Entire Agreement; Waiver; Severability</h2>
  <p>These Terms (together with the Privacy Policy referenced above) are the entire agreement relating to the Beta and supersede all prior or contemporaneous agreements on that subject. No waiver is effective unless in writing. If any provision is found unenforceable, the remainder will continue in effect, and the unenforceable provision will be modified to the minimum extent necessary to be enforceable.</p>

  <hr>
  <p><strong>Contact:</strong> DeckCheck Technologies, LLC, 3 Elmcrest Terrace, Norwalk, CT 06850 &bull; <a href="mailto:admin@deckcheck.app">admin@deckcheck.app</a></p>
</article>

                        </div>
                    </div>

                    <!-- Agreement Checkbox -->
                    <div class="flex items-start space-x-2">
                        <input id="accept" type="checkbox" name="accept" disabled class="mt-1" />
                        <label for="accept" class="text-sm text-[#344053]">I have read and agree to the Terms & Conditions</label>
                    </div>

                    <!-- Form Actions -->
                    <div class="p-6 bg-white border-t border-[#e4e7ec] flex justify-between" id="form-actions">
                        <a href="{{ route('invitations.accept.avatar', ['token' => $token]) }}">
                            <div class="px-[18px] py-2.5 bg-white rounded-lg border border-[#cfd4dc] flex items-center gap-2">
                                <i class="fa-solid fa-arrow-left text-[#344053]"></i>
                                <span class="text-[#344053] text-base font-medium">Back</span>
                            </div>
                        </a>

                        <button type="submit" id="submitButton" disabled
                            class="px-[18px] py-2.5 bg-[#7e56d8] rounded-lg border border-[#7e56d8] text-white font-medium flex items-center gap-2 opacity-50 cursor-not-allowed">
                            Join Vessel
                            <i class="fa-solid fa-check text-white"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scroll Detection Script -->
    <script>
        const termsScroll = document.getElementById('terms-scroll');
        const checkbox = document.getElementById('accept');
        const submitButton = document.getElementById('submitButton');

        termsScroll.addEventListener('scroll', () => {
            const isScrolledToBottom = termsScroll.scrollTop + termsScroll.clientHeight >= termsScroll.scrollHeight - 10;

            if (isScrolledToBottom) {
                checkbox.disabled = false;
            }
        });

        checkbox.addEventListener('change', () => {
            if (checkbox.checked) {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                submitButton.disabled = true;
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            }
        });
    </script>

@endsection
