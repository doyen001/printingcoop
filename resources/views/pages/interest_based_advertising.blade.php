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

            <p>Printing Coop ("Printing Coop," "we," "our," or "us") may partner with ad networks and other ad serving providers ("Advertising Providers") that serve ads on behalf of us and others on non-affiliated platforms. Some of those ads may be personalized, meaning that they are intended to be relevant to you based on information Advertising Providers collect about your use of the Services and other sites or apps over time, including information about relationships among different browsers and devices. This type of advertising is known as interest-based advertising.</p>

            <h2>What is Interest-Based Advertising?</h2>
            <p>Interest-based advertising (also called online behavioral advertising) uses information collected across multiple websites and apps to predict your preferences and show you ads that are more likely to be of interest to you. This information is typically collected through cookies, pixels, web beacons, and similar technologies.</p>
            <p>For example, if you browse printing products on our website, you may later see ads for our products on other websites you visit. This is because Advertising Providers recognize your browser or device and know that you recently visited our site.</p>

            <h2>How Does It Work?</h2>
            <p>When you visit our Services, Advertising Providers may place or recognize cookies, pixels, or similar technologies on your browser or device. These technologies allow Advertising Providers to:</p>
            <ul>
                <li>Collect information about your browsing activity across websites and apps;</li>
                <li>Use that information to categorize your likely interests;</li>
                <li>Show you ads that are tailored to your interests;</li>
                <li>Measure the effectiveness of ads shown to you;</li>
                <li>Limit the number of times you see a particular ad.</li>
            </ul>

            <h2>Your Choices</h2>
            <p>You have choices regarding interest-based advertising:</p>
            <ul>
                <li><strong>DAA Webchoices Tool:</strong> Visit <a href="https://www.aboutads.info/choices" target="_blank">www.aboutads.info/choices</a> to learn more about interest-based advertising and to opt out of advertising on websites by companies participating in the DAA self-regulatory program.</li>
                <li><strong>DAA AppChoices:</strong> Download the DAA's AppChoices tool at <a href="https://youradchoices.com/appchoices" target="_blank">https://youradchoices.com/appchoices</a> to exercise choices regarding interest-based advertising on your mobile device.</li>
                <li><strong>Browser Settings:</strong> Most browsers allow you to manage cookies through their settings. You can choose to block or delete cookies, though this may affect the functionality of some websites.</li>
                <li><strong>Do Not Sell:</strong> Click the "Do Not Sell or Share My Personal Information" link in the footer of our website to opt out of the sharing of personal information for interest-based advertising.</li>
            </ul>

            <h2>Important Notes</h2>
            <p>If you delete your cookies or use a different browser or mobile device, you may need to renew your opt-out choices exercised through the DAA Webchoices tool. Note that electing to opt out will not stop advertising from appearing in your browser or applications. It may make the ads you see less relevant to your interests.</p>

            <h2>Analytics Services</h2>
            <p>We may also work with third parties that collect data about your use of our Services and other sites or apps over time for non-advertising purposes. Printing Coop uses Google Analytics and other third-party services to improve the performance of our Services and for analytics and marketing purposes.</p>
            <p>For more information:</p>
            <ul>
                <li><strong>Google Analytics:</strong> Visit <a href="https://www.google.com/policies/privacy/partners" target="_blank">www.google.com/policies/privacy/partners</a> to learn more, or <a href="https://tools.google.com/dlpage/gaoptout" target="_blank">opt out here</a>.</li>
            </ul>

            <h2>Contact Us</h2>
            <p>If you have any questions about interest-based advertising practices, please contact us at <a href="mailto:info@printing.coop">info@printing.coop</a> or by phone at 1-888-384-8043 or 514-544-8043.</p>
        </div>
    </div>
</section>
@endsection
