@extends('elements.app')

@section('title', $page_title)

@push('styles')
<style>
.legal-page-section {
    padding: 3rem 0;
    background: #f8f9fa;
    min-height: 60vh;
}

.legal-page-container {
    max-width: 900px;
    margin: 0 auto;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    padding: 3rem;
}

.legal-page-container h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #222;
    margin-bottom: 0.5rem;
}

.legal-page-container .effective-date {
    color: #666;
    font-size: 0.95rem;
    margin-bottom: 2rem;
    display: block;
}

.legal-page-container p {
    color: #444;
    font-size: 0.95rem;
    line-height: 1.7;
    margin-bottom: 1rem;
}

.legal-page-container h2 {
    font-size: 1.3rem;
    font-weight: 600;
    color: #222;
    margin-top: 2rem;
    margin-bottom: 0.75rem;
}

.legal-page-container h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
}

.legal-page-container ul {
    padding-left: 1.5rem;
    margin-bottom: 1rem;
}

.legal-page-container ul li {
    color: #444;
    font-size: 0.95rem;
    line-height: 1.7;
    margin-bottom: 0.4rem;
}

.legal-page-container a {
    color: #007bff;
    text-decoration: none;
}

.legal-page-container a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .legal-page-container {
        padding: 1.5rem;
        margin: 0 1rem;
    }
    .legal-page-container h1 {
        font-size: 1.5rem;
    }
}
</style>
@endpush

@section('content')
<section class="legal-page-section">
    <div class="container">
        <div class="legal-page-container">
            <h1>{{ $page_title }}</h1>
            <span class="effective-date">Effective Date: February 24th, 2025</span>

            <p>Printing Coop ("Printing Coop," "we," "our," or "us") provides this Privacy Policy to describe how we collect, use, share, and otherwise process the information of individuals who visit our websites, located at www.printing.coop, www.imprimeur.coop, use our services, interact with any of our services that link to this Policy, or interact with other websites or applications that link to this Policy (collectively "Services").</p>

            <h2>What information do we collect?</h2>
            <p>We collect information that you voluntarily provide to us, including your name, email address, mailing address, phone number, fax number, payment information, employment information, images, and any other information that you choose to provide us. If you create an account on our Services, we also collect your log-in information, such as your username and password. We collect this information when you sign up for our Services, join our mailing list, leave a review, use the chat function, upload information to customize your orders, contact us, or interact with us in any other manner.</p>
            <p>We (and other entities) automatically collect information relating to your interactions with us and the Services, including, but not limited to, browser type, IP address, pages visited and other activities on the Services, device type, time and date of visit, and other information we collect through the use of cookies, pixels, and similar technology. We may use cookies, pixels, or similar technologies to collect such information for advertising and other purposes. Please see the "Digital Advertising &amp; Analytics" section of this Privacy Policy to learn more about the use of this information and the choices available to you.</p>

            <h2>How do we use your information?</h2>
            <p>We may use your personal information for various purposes, including the following:</p>
            <ul>
                <li>To provide the products or services you request, such as to offer our products to you;</li>
                <li>To contact you, including to respond to your inquiries;</li>
                <li>To maintain, operate, customize, and improve the Services, including for analytics, research, payment processing;</li>
                <li>For advertising and marketing, including to send updates, promotions, and marketing materials that may be of interest to you;</li>
                <li>To comply with law enforcement and maintain the security of our Services; or</li>
                <li>With your consent or as otherwise disclosed at the time of collection or use.</li>
            </ul>

            <h2>Do we share your information?</h2>
            <p>We may share the information we collect with third parties, including:</p>
            <ul>
                <li>With third parties that provide services on our behalf, such as payment processors, data hosting providers, and email service providers;</li>
                <li>With any affiliates or joint venture partners that we may have in the future;</li>
                <li>With Advertising Partners through third-party cookies, pixels, or similar technologies that may transmit (in real-time and directly from your web browser) GET requests, page viewing history and requests, and similar information about your activities on the Services or as otherwise described in the "Digital Advertising &amp; Analytics" section;</li>
                <li>As part of a sale, merger or acquisition, or other transfer of all or part of our assets including as part of a bankruptcy proceeding;</li>
                <li>Pursuant to a subpoena, court order, governmental inquiry, or other legal process or as otherwise required or requested by law, regulation, or government authority programs, or to protect our rights or the rights or safety of third parties; or</li>
                <li>With your consent or as otherwise disclosed at the time of data collection or sharing.</li>
            </ul>
            <p>We may share information that has been deidentified without restriction.</p>
            <p>The Services may offer interactive features, such as the ability to leave reviews or respond to blogposts, that you can use to communicate with other site visitors or to submit and post your own content. If you disclose information in one of these forums, this information can be viewed, collected, and used by others.</p>

            <h2>Digital Advertising &amp; Analytics</h2>
            <p>We may partner with ad networks and other ad serving providers ("Advertising Providers") that serve ads on behalf of us and others on non-affiliated platforms. Some of those ads may be personalized, meaning that they are intended to be relevant to you based on information Advertising Providers collect about your use of the Services and other sites or apps over time, including information about relationships among different browsers and devices. This type of advertising is known as interest-based advertising.</p>
            <p>You may visit the DAA Webchoices tool at <a href="https://www.aboutads.info/choices" target="_blank">www.aboutads.info/choices</a> to learn more about this type of advertising and how to opt out of this advertising on websites by companies participating in the DAA self-regulatory program. You can also exercise choices regarding interest-based advertising on your mobile device by downloading the appropriate version of the DAA's AppChoices tool at <a href="https://youradchoices.com/appchoices" target="_blank">https://youradchoices.com/appchoices</a>.</p>
            <p>If you delete your cookies or use a different browser or mobile device, you may need to renew your opt-out choices exercised through the DAA Webchoices tool. Note that electing to opt out will not stop advertising from appearing in your browser or applications. It may make the ads you see less relevant to your interests.</p>
            <p>We may also work with third parties that collect data about your use of our Services and other sites or apps over time for non-advertising purposes. Printing Coop uses Google Analytics and other third-party services to improve the performance of our Services and for analytics and marketing purposes. For more information about how Google Analytics collects and uses data when you use our Services, visit <a href="https://www.google.com/policies/privacy/partners" target="_blank">www.google.com/policies/privacy/partners</a>. To opt out Google Analytics, visit <a href="https://tools.google.com/dlpage/gaoptout" target="_blank">tools.google.com/dlpage/gaoptout</a>.</p>
            <p>Additionally, your browser may offer tools to limit the use of cookies or to delete cookies; however, if you use these tools, our Services may not function as intended.</p>

            <h2>Third-Party Links and Tools</h2>
            <p>The Services may provide links to third-party websites or apps, including social media pages. We do not control the privacy practices of those websites or apps, and they are not covered by this Privacy Policy. You should review the privacy notices of other websites or apps that you use to learn about their data practices.</p>
            <p>The Services may also include integrated social media tools or "plug-ins," such as social networking tools offered by third parties. If you use these tools to share personal information or you otherwise interact with these features on the services, those companies may collect information about you and may use and share such information in accordance with your account settings, including by sharing such information with the general public. Your interactions with third-party companies and your use of their features are governed by the privacy notices of the companies that provide those features. We encourage you to carefully read the privacy notices of any accounts you create and use.</p>

            <h2>State Privacy Rights</h2>
            <p>Residents of certain states have rights under state privacy laws with respect to personal information we collect. If you are a resident of such states, this section of the Privacy Policy contains disclosures required by law and explains rights that may be available to you.</p>

            <h3>Personal Information We Collect and Disclose</h3>
            <p>In the preceding 12 months, we collected and disclosed the following categories of personal information about consumers and business contacts.</p>

            <h3>Categories of Personal Information</h3>
            <ul>
                <li>Personal and online identifiers (such as first and last name, email address, or unique online identifiers)</li>
                <li>Payment information (such as bank account number, credit card number, debit card number, or any other financial information)</li>
                <li>Commercial or transactions information (such as records of products or services purchased, obtained or considered)</li>
                <li>Internet or other electronic network activity information (such as browsing history, search history, interactions with a website, email, application, or advertisement)</li>
                <li>Coarse geolocation information</li>
                <li>Professional or employment-related information</li>
                <li>Inferences drawn from the above information about your predicted characteristics and preferences</li>
                <li>Other information about you that is linked to the personal information above</li>
            </ul>
            <p>We do not collect "sensitive personal information" as that term is defined under the California Consumer Privacy Act.</p>

            <h3>Categories of Sources</h3>
            <p>We collect Personal Information from the following categories of sources:</p>
            <ul>
                <li>Consumers;</li>
                <li>Business contacts;</li>
                <li>Data analytics providers;</li>
                <li>Advertising companies and networks;</li>
                <li>Service providers; and</li>
                <li>Affiliates and joint venture partners.</li>
            </ul>

            <h3>Why We Collect, Use, and Disclose Personal Information</h3>
            <p>We use and disclose the personal information we collect for our commercial and business purposes, as further described in the "How do we use your information?" and "Do we share your information?" sections of this Privacy Policy.</p>
            <p>We share personal and online identifiers, commercial or transactions information, Internet or other electronic network activity information, and inferences with advertising companies and networks as well as affiliates and joint venture partners for commercial purposes, including marketing and interest-based advertising, but do not otherwise engage in "sales" of personal information as defined by state laws.</p>
            <p>We disclose all categories of personal information designated above to the categories of third parties listed below for business purposes:</p>
            <ul>
                <li>Service providers;</li>
                <li>Data analytics providers;</li>
                <li>Advertising companies and networks;</li>
                <li>Affiliates and joint venture partners; and</li>
                <li>Government entities or other third parties if required by law or legal process.</li>
            </ul>
            <p>We may use and share deidentified information (including aggregated information) to the extent permitted by applicable law. When we use deidentified information, we maintain the information in deidentified form and do not attempt to reidentify it, except to check whether our deidentification processes satisfy the requirements of applicable law.</p>

            <h3>Your Rights Regarding Personal Information</h3>
            <p>Residents of certain states have rights with respect to the personal information collected by businesses. You may be able to exercise the following rights regarding personal information, subject to certain exceptions and limitations:</p>
            <ul>
                <li>The right to confirm whether we are processing personal information about you.</li>
                <li>The right to know the categories and specific pieces of personal information we collect, use, disclose, and sell about you; the categories of sources from which we collected personal information about you; our purposes for collecting or selling personal information about you; the categories of personal information about you that we have sold or disclosed for a business purpose; and the categories of third parties with which we have shared personal information.</li>
                <li>Depending on your state, the right to receive a portable and readily usable copy of the personal information we have collected about you, to the extent feasible.</li>
                <li>The right to correct inaccuracies in the personal information we have collected about you.</li>
                <li>The right to request that we delete the personal information we have collected from you.</li>
                <li>The right to opt out of sales of personal information or the sharing of personal information for interest-based advertising. Please note that if you opt out of certain practices, we may be unable to provide you with some services. Additionally, we do not knowingly sell or share personal data of individuals under 16.</li>
                <li>The right not to receive discriminatory treatment for the exercise of the privacy rights conferred by the CCPA.</li>
            </ul>
            <p>To exercise any of the above rights, please contact us using the following information and submit the required verifying information, as further described below:</p>
            <ul>
                <li>To opt out of sales of personal information or the sharing of personal information for interest-based advertising, click the "Do Not Sell or Share My Personal Information" link in the footer of the website or settings sections of our apps.</li>
                <li>For all other requests, by phone at 1-888-384-8043 or 514-544-8043, or via our webform <a href="{{ url('Pages/contactUs') }}">here</a>.</li>
            </ul>
            <p>Additionally, for residents of certain states, if you have submitted a request that we have not reasonably fulfilled, you may contact us to appeal our decision by sending an email with the subject link "Appeal" to info@printing.coop.</p>

            <h3>Verification Process and Required Information</h3>
            <p>Note that we may need to request additional information from you to verify your identity or understand the scope of your request, although you will not be required to create an account with us to submit a request or have it fulfilled. We will require you to provide, at a minimum, name, email address and user ID to match the personal information GotPrint maintains about you.</p>

            <h3>Authorized Agent</h3>
            <p>Depending on your state of residence, you may designate an authorized agent to make a requests on your behalf by submitting a request for an Authorized Agent Designation (including the name and contact information for you and for your authorized agent) via email to info@printing.coop. We may require the agent to provide us with proof that you have authorized the agent to make requests on your behalf prior to accepting requests from the agent.</p>

            <h2>Data Retention</h2>
            <p>We will retain each category of personal information we collect in accordance with applicable laws and as reasonably necessary for our processing purposes set out under this Policy. When it is no longer necessary to process personal information for these purposes, we will deidentify, aggregate, or delete this data to the extent possible.</p>

            <h2>Your Choices</h2>
            <p>To update your contact information or change your communication preferences, including to opt out of marketing communications from us, you can update your preferences by accessing "My Account" under "Email Settings" or by contacting us through the methods provided in our Contact Us form. You may also unsubscribe from our email communications by using the link provided in the email.</p>
            <p>For choices with respect to third-party interest-based advertising activities, please see the "Digital Advertising &amp; Analytics" section above.</p>

            <h2>Changes to Our Privacy Policy</h2>
            <p>If our information practices change, we will post these changes on this page. We encourage you to visit this page periodically to learn of any updates.</p>

            <h2>Contact Us</h2>
            <p>If you have any questions, comments, or concerns about our Privacy Policy, please contact us at <a href="mailto:info@printing.coop">info@printing.coop</a> or by phone at 1-888-384-8043 or 514-544-8043.</p>
        </div>
    </div>
</section>
@endsection
