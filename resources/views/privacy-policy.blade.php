@extends('layouts.main')

@section('title', 'Privacy Policy - Aether & Leaf.Co')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Page Header -->
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold mb-3" style="color: #6A8F4E;">Privacy Policy</h1>
                    <p class="lead text-muted">Last Updated: {{ date('F d, Y') }}</p>
                    <p class="text-muted">At Aether & Leaf.Co, your privacy matters. We want you to know what data we
                        collect and how we use it.</p>
                </div>

                <!-- Introduction -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">Introduction</h3>
                        <p>Welcome to Aether & Leaf.Co ("we", "us", or "our"). This Privacy Policy explains how we handle
                            your personal information when you use our website <a href="/">aetherleaf.com</a>, our app, or
                            shop with us.</p>
                        <p>If you don’t agree with any part of this policy, please avoid using our services.</p>
                    </div>
                </div>

                <!-- Information We Collect -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">Information We Collect</h3>

                        <h5 class="mt-4 mb-3">What You Provide</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0"><strong>Account Info:</strong> Name, email, password,
                                phone, profile picture.</li>
                            <li class="list-group-item border-0 px-0"><strong>Order Info:</strong> Billing/shipping
                                addresses, payment details, order history.</li>
                            <li class="list-group-item border-0 px-0"><strong>Messages & Feedback:</strong> Emails, chats,
                                or forms you submit.</li>
                            <li class="list-group-item border-0 px-0"><strong>Preferences:</strong> Wishlists, saved
                                addresses, gardening interests.</li>
                        </ul>

                        <h5 class="mt-4 mb-3">Automatically Collected Info</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0"><strong>Device Info:</strong> IP address, browser
                                type, OS, device type.</li>
                            <li class="list-group-item border-0 px-0"><strong>Usage Info:</strong> Pages visited, clicks,
                                search queries, referring websites.</li>
                            <li class="list-group-item border-0 px-0"><strong>Location Info:</strong> Approximate location
                                via IP or GPS if you allow it.</li>
                            <li class="list-group-item border-0 px-0"><strong>Cookies & Tracking:</strong> To improve your
                                browsing experience.</li>
                        </ul>
                    </div>
                </div>

                <!-- How We Use Your Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">How We Use Your Info</h3>
                        <p>We use your data mainly to provide and improve our services. This includes:</p>
                        <ul>
                            <li>Processing orders and delivering products</li>
                            <li>Managing your account and providing support</li>
                            <li>Personalizing recommendations based on your preferences</li>
                            <li>Sending emails, promotions, or newsletters (only if you opt in)</li>
                            <li>Improving website performance and security</li>
                        </ul>
                    </div>
                </div>

                <!-- Data Sharing & Disclosure -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">Data Sharing & Disclosure</h3>
                        <p>We respect your privacy and do not sell your data. We only share information in specific
                            situations:</p>
                        <ul>
                            <li>With trusted service providers who help us run the business</li>
                            <li>When legally required or to protect our rights</li>
                            <li>During business transfers like mergers or sales</li>
                            <li>With your consent for specific purposes</li>
                        </ul>
                    </div>
                </div>

                <!-- Data Security -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">Data Security</h3>
                        <p>We use industry-standard security measures such as SSL, access controls, and regular backups to
                            protect your data. Please note that no online system is 100% secure.</p>
                    </div>
                </div>

                <!-- Your Rights -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">Your Rights</h3>
                        <p>You can:</p>
                        <ul>
                            <li>Access and update your account info</li>
                            <li>Request corrections or deletions</li>
                            <li>Manage cookies and marketing preferences</li>
                            <li>Contact us at <a href="mailto:privacy@aetherleaf.com">aether&leaf@.com</a> for any
                                request</li>
                        </ul>
                    </div>
                </div>

                <!-- Children's Privacy -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">Children's Privacy</h3>
                        <p>We do not knowingly collect information from children under 16. If you believe a child has
                            provided us info, please contact us and we will remove it.</p>
                    </div>
                </div>

                <!-- Contact -->
                <div class="card border-success shadow-sm mb-4" style="border-width: 2px;">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">Contact Us</h3>
                        <p>If you have questions about this Privacy Policy, reach out to us:</p>
                        <ul>
                            <li>Email: <a href="mailto:privacy@aetherleaf.com">aether&leaf@.com</a></li>
                            <li>Phone: <a href="tel:+60123456789">+60 17-274 3933</a></li>
                            <li>Address: 123 Green Street, Kuala Lumpur, Malaysia</li>
                        </ul>
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="text-center mt-5 p-4 border-top">
                    <p class="text-muted">By using our website, you acknowledge that you have read and understood this
                        Privacy Policy.</p>
                    <a href="/" class="btn btn-success">Return to Homepage</a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        h3,
        h4,
        h5,
        h6 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        a {
            color: #6A8F4E;
            text-decoration: none;
        }

        a:hover {
            color: #5a7e3e;
            text-decoration: underline;
        }
    </style>
@endsection