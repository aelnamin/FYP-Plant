@extends('layouts.app')

@section('title', 'Privacy Policy - Aether & Leaf.Co')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Page Header -->
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold mb-3" style="color: #6A8F4E;">Privacy Policy</h1>
                    <p class="lead text-muted">Last Updated: {{ date('F d, Y') }}</p>
                    <p class="text-muted">At Aether & Leaf.Co, we are committed to protecting your privacy and personal
                        information.</p>
                </div>

                <!-- Introduction -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">Introduction</h3>
                        <p>Welcome to Aether & Leaf.Co ("we," "our," or "us"). This Privacy Policy explains how we collect,
                            use, disclose, and safeguard your information when you visit our website <a
                                href="/">aetherleaf.com</a>, use our mobile application, or make a purchase from us.</p>
                        <p>Please read this privacy policy carefully. If you do not agree with the terms of this privacy
                            policy, please do not access the site or use our services.</p>
                    </div>
                </div>

                <!-- Information We Collect -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">Information We Collect</h3>

                        <h5 class="mt-4 mb-3">Personal Information You Provide</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0">
                                <strong>Account Information:</strong> Name, email address, password, phone number, and
                                profile picture when you create an account
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <strong>Purchase Information:</strong> Billing and shipping addresses, payment information,
                                order history, and transaction details
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <strong>Communication Data:</strong> Messages, inquiries, and feedback you send to us
                                through contact forms, email, or chat
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <strong>Profile Information:</strong> Preferences, wishlists, saved addresses, and gardening
                                interests
                            </li>
                        </ul>

                        <h5 class="mt-4 mb-3">Information Collected Automatically</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 px-0">
                                <strong>Device Information:</strong> IP address, browser type, operating system, device
                                type, and mobile network information
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <strong>Usage Data:</strong> Pages visited, time spent on pages, click patterns, search
                                queries, and referring website
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <strong>Location Data:</strong> Approximate location based on IP address or precise location
                                with your permission
                            </li>
                            <li class="list-group-item border-0 px-0">
                                <strong>Cookies and Tracking:</strong> Cookies, web beacons, and similar technologies to
                                enhance your browsing experience
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- How We Use Your Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">How We Use Your Information</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-cart-check-fill text-success fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold">Order Processing</h6>
                                        <p class="small text-muted mb-0">Process transactions, deliver products, and provide
                                            order updates</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-person-check-fill text-success fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold">Account Management</h6>
                                        <p class="small text-muted mb-0">Create and manage your account, authenticate users,
                                            and provide customer support</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-megaphone-fill text-success fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold">Personalization</h6>
                                        <p class="small text-muted mb-0">Recommend plants based on your preferences and
                                            browsing history</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-shield-check-fill text-success fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold">Security & Compliance</h6>
                                        <p class="small text-muted mb-0">Protect against fraud, comply with legal
                                            obligations, and enforce our terms</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-envelope-check-fill text-success fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold">Marketing Communications</h6>
                                        <p class="small text-muted mb-0">Send promotional emails, newsletters, and special
                                            offers (with your consent)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-graph-up-arrow text-success fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold">Analytics & Improvement</h6>
                                        <p class="small text-muted mb-0">Analyze usage patterns and improve our website,
                                            products, and services</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Sharing & Disclosure -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">Data Sharing & Disclosure</h3>
                        <p>We may share your information in the following circumstances:</p>

                        <div class="alert alert-light border mb-3">
                            <h6 class="fw-bold">Service Providers</h6>
                            <p class="mb-0">With trusted third-party vendors who assist us in operating our website,
                                conducting business, or servicing you (payment processors, shipping companies, hosting
                                providers).</p>
                        </div>

                        <div class="alert alert-light border mb-3">
                            <h6 class="fw-bold">Legal Requirements</h6>
                            <p class="mb-0">When required by law or to respond to legal process, protect our rights, or
                                ensure the safety of our users.</p>
                        </div>

                        <div class="alert alert-light border mb-3">
                            <h6 class="fw-bold">Business Transfers</h6>
                            <p class="mb-0">In connection with a merger, acquisition, or sale of all or a portion of our
                                assets.</p>
                        </div>

                        <div class="alert alert-light border mb-3">
                            <h6 class="fw-bold">With Your Consent</h6>
                            <p class="mb-0">For any other purpose disclosed to you when you provide the information or with
                                your explicit consent.</p>
                        </div>

                        <p class="text-muted mt-3"><small>We do not sell your personal information to third parties for
                                their marketing purposes.</small></p>
                    </div>
                </div>

                <!-- Data Security -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">Data Security</h3>
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <p>We implement appropriate technical and organizational security measures to protect your
                                    personal information from unauthorized access, alteration, disclosure, or destruction.
                                    These measures include:</p>
                                <ul>
                                    <li>SSL encryption for data transmission</li>
                                    <li>Regular security assessments and monitoring</li>
                                    <li>Access controls and authentication procedures</li>
                                    <li>Secure payment processing with PCI DSS compliance</li>
                                    <li>Regular data backups and disaster recovery plans</li>
                                </ul>
                                <p class="text-muted"><small>While we strive to protect your information, no method of
                                        transmission over the Internet or electronic storage is 100% secure.</small></p>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="bg-light rounded p-4">
                                    <i class="bi bi-shield-lock-fill text-success" style="font-size: 3rem;"></i>
                                    <p class="mt-3 fw-bold">Your Data is Protected</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Your Rights -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">Your Rights & Choices</h3>
                        <p>Depending on your location, you may have the following rights regarding your personal
                            information:</p>

                        <div class="row mt-4">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <i class="bi bi-eye-fill text-success me-3"></i>
                                    <div>
                                        <h6 class="fw-bold">Right to Access</h6>
                                        <p class="small text-muted mb-0">Request access to the personal data we hold about
                                            you</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <i class="bi bi-pencil-square text-success me-3"></i>
                                    <div>
                                        <h6 class="fw-bold">Right to Rectification</h6>
                                        <p class="small text-muted mb-0">Request correction of inaccurate or incomplete data
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <i class="bi bi-trash-fill text-success me-3"></i>
                                    <div>
                                        <h6 class="fw-bold">Right to Deletion</h6>
                                        <p class="small text-muted mb-0">Request deletion of your personal data under
                                            certain conditions</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <i class="bi bi-pause-circle-fill text-success me-3"></i>
                                    <div>
                                        <h6 class="fw-bold">Right to Restrict Processing</h6>
                                        <p class="small text-muted mb-0">Request restriction of processing your personal
                                            data</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <i class="bi bi-clipboard-data-fill text-success me-3"></i>
                                    <div>
                                        <h6 class="fw-bold">Right to Data Portability</h6>
                                        <p class="small text-muted mb-0">Receive your data in a structured, commonly used
                                            format</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <i class="bi bi-x-circle-fill text-success me-3"></i>
                                    <div>
                                        <h6 class="fw-bold">Right to Object</h6>
                                        <p class="small text-muted mb-0">Object to processing of your personal data</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-light rounded">
                            <p class="mb-2"><strong>To exercise these rights, please:</strong></p>
                            <ol class="mb-0">
                                <li>Log into your account and update your information in the settings</li>
                                <li>Contact us at <a href="mailto:privacy@aetherleaf.com">privacy@aetherleaf.com</a></li>
                                <li>Use the privacy tools available in your account dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Cookies -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">Cookies & Tracking Technologies</h3>
                        <p>We use cookies and similar tracking technologies to track activity on our website and hold
                            certain information.</p>

                        <div class="table-responsive mt-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type of Cookie</th>
                                        <th>Purpose</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Essential Cookies</strong></td>
                                        <td>Required for basic site functionality (login, cart, checkout)</td>
                                        <td>Session</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Preference Cookies</strong></td>
                                        <td>Remember your settings and preferences</td>
                                        <td>Persistent</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Analytics Cookies</strong></td>
                                        <td>Help us understand how visitors interact with our website</td>
                                        <td>Persistent</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Marketing Cookies</strong></td>
                                        <td>Track browsing habits to display relevant advertisements</td>
                                        <td>Persistent</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <p>You can control cookies through your browser settings. However, disabling cookies may affect
                                your ability to use certain features of our website.</p>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle-fill text-success me-2"></i>
                                <p class="mb-0"><small>You can manage your cookie preferences through our <a href="#">Cookie
                                            Settings</a> tool.</small></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Children's Privacy -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">Children's Privacy</h3>
                        <div class="d-flex align-items-start">
                            <i class="bi bi-shield-exclamation-fill text-warning me-3 mt-1"></i>
                            <div>
                                <p>Our services are not intended for individuals under the age of 16. We do not knowingly
                                    collect personal information from children under 16.</p>
                                <p>If you are a parent or guardian and believe your child has provided us with personal
                                    information, please contact us immediately. If we learn we have collected personal
                                    information from a child under 16, we will delete that information promptly.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Changes to Policy -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">Changes to This Privacy Policy</h3>
                        <p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting
                            the new Privacy Policy on this page and updating the "Last Updated" date.</p>
                        <p>You are advised to review this Privacy Policy periodically for any changes. Changes to this
                            Privacy Policy are effective when they are posted on this page.</p>
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-bell-fill me-2"></i>
                            <strong>Notification:</strong> Significant changes will be communicated to you via email or
                            through a notice on our website.
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card border-success shadow-sm mb-4" style="border-width: 2px;">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-3" style="color: #6A8F4E;">Contact Us</h3>
                        <p>If you have any questions about this Privacy Policy, please contact us:</p>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-light rounded-circle p-3 me-3">
                                        <i class="bi bi-envelope-fill text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0">Email</h6>
                                        <a href="mailto:privacy@aetherleaf.com">privacy@aetherleaf.com</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-light rounded-circle p-3 me-3">
                                        <i class="bi bi-telephone-fill text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0">Phone</h6>
                                        <a href="tel:+60123456789">+60 12-345 6789</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-light rounded-circle p-3 me-3">
                                        <i class="bi bi-geo-alt-fill text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0">Address</h6>
                                        <p class="mb-0">123 Green Street, Kuala Lumpur, Malaysia</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-light rounded-circle p-3 me-3">
                                        <i class="bi bi-clock-fill text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0">Response Time</h6>
                                        <p class="mb-0">Within 48 hours for privacy-related inquiries</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-light rounded">
                            <p class="mb-0"><strong>Data Protection Officer:</strong> If you are located in the European
                                Economic Area (EEA) and have concerns about our data practices, you may contact our Data
                                Protection Officer at <a href="mailto:dpo@aetherleaf.com">dpo@aetherleaf.com</a>.</p>
                        </div>
                    </div>
                </div>

                <!-- Acceptance -->
                <div class="text-center mt-5 p-4 border-top">
                    <p class="text-muted">By using our website or services, you acknowledge that you have read and
                        understood this Privacy Policy.</p>
                    <div class="d-flex justify-content-center gap-3 mt-3">
                        <a href="{{ route('auth.login') }}" class="btn btn-outline-success">Back to Login</a>
                        <a href="/" class="btn btn-success">Return to Homepage</a>
                    </div>
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

        .list-group-item {
            padding-left: 0;
            padding-right: 0;
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