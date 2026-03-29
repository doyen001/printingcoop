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

            <p>Welcome to the websites operated by Printing Coop ("Printing Coop," "we," "our," or "us"). By accessing or using our websites located at www.printing.coop, www.imprimeur.coop, or any related applications or services (collectively, the "Services"), you agree to be bound by these Terms of Use.</p>

            <h2>1. Acceptance of Terms</h2>
            <p>By using the Services, you agree to these Terms of Use and our Privacy Policy. If you do not agree, please do not use the Services. We reserve the right to modify these Terms at any time. Your continued use of the Services after any changes constitutes acceptance of the updated Terms.</p>

            <h2>2. Use of Services</h2>
            <p>You may use the Services only for lawful purposes and in accordance with these Terms. You agree not to:</p>
            <ul>
                <li>Use the Services in any way that violates applicable laws or regulations;</li>
                <li>Use the Services for any unauthorized or fraudulent purpose;</li>
                <li>Interfere with or disrupt the Services or servers or networks connected to the Services;</li>
                <li>Attempt to gain unauthorized access to any portion of the Services;</li>
                <li>Use any automated means to access or collect data from the Services without our express permission.</li>
            </ul>

            <h2>3. Account Registration</h2>
            <p>To access certain features of the Services, you may be required to create an account. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account. You agree to notify us immediately of any unauthorized use of your account.</p>

            <h2>4. Orders and Payment</h2>
            <p>When you place an order through our Services, you agree to provide accurate and complete information. All orders are subject to acceptance and availability. We reserve the right to refuse or cancel any order at our discretion. Payment must be made at the time of order placement using the accepted payment methods listed on our website.</p>

            <h2>5. Intellectual Property</h2>
            <p>All content on the Services, including text, graphics, logos, images, and software, is the property of Printing Coop or its licensors and is protected by copyright, trademark, and other intellectual property laws. You may not reproduce, distribute, modify, or create derivative works from any content without our prior written consent.</p>

            <h2>6. User Content</h2>
            <p>You are solely responsible for any content you upload, submit, or transmit through the Services, including designs, images, and files for printing. You represent and warrant that you have all necessary rights to such content and that it does not infringe upon the rights of any third party.</p>

            <h2>7. Limitation of Liability</h2>
            <p>To the fullest extent permitted by law, Printing Coop shall not be liable for any indirect, incidental, special, consequential, or punitive damages, or any loss of profits or revenues, whether incurred directly or indirectly, or any loss of data, use, goodwill, or other intangible losses resulting from your use of the Services.</p>

            <h2>8. Indemnification</h2>
            <p>You agree to indemnify and hold harmless Printing Coop, its officers, directors, employees, and agents from any claims, damages, losses, liabilities, and expenses (including reasonable attorneys' fees) arising from your use of the Services or any violation of these Terms.</p>

            <h2>9. Termination</h2>
            <p>We may terminate or suspend your access to the Services at any time, without prior notice or liability, for any reason, including if you breach these Terms. Upon termination, your right to use the Services will immediately cease.</p>

            <h2>10. Governing Law</h2>
            <p>These Terms shall be governed by and construed in accordance with the laws of the Province of Quebec, Canada, without regard to its conflict of law provisions. Any disputes arising under these Terms shall be resolved in the courts located in Montreal, Quebec.</p>

            <h2>11. Contact Us</h2>
            <p>If you have any questions about these Terms of Use, please contact us at <a href="mailto:info@printing.coop">info@printing.coop</a> or by phone at 1-888-384-8043 or 514-544-8043.</p>
        </div>
    </div>
</section>
@endsection
