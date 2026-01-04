@extends('layouts.main')

@section('title', 'Help Center - Aether & Leaf.Co')

@section('content')
    <div class="help-center-container">
        <div class="container">
            <!-- Page Header -->
            <header class="page-header">
                <h1><i class="fas fa-hands-helping"></i> Help Center</h1>
                <p>Get assistance with your orders and account</p>
            </header>

            <!-- Search Section -->
            <section class="search-section">
                <p style="margin-bottom: 15px; color: #666;">Search for answers:</p>
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Type your question here...">
                    <button id="searchBtn">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
            </section>


            <!-- FAQ Categories -->
            <section class="faq-section">
                <h2 class="section-title">Common Questions</h2>

                <div class="faq-category">
                    <h3><i class="fas fa-shopping-cart"></i> Ordering & Shipping</h3>
                    <ul class="faq-list">
                        <li class="faq-item" data-question="How do I place an order?">
                            <span class="faq-question">How do I place an order?</span>
                            <p class="faq-answer">To place an order, browse our catalog, add items to your cart, and proceed
                                to checkout. Follow the prompts to complete your payment.</p>
                        </li>
                        <li class="faq-item" data-question="How long does shipping take?">
                            <span class="faq-question">How long does shipping take?</span>
                            <p class="faq-answer">Shipping typically takes 3-7 business days depending on your location and
                                the items in your order.</p>
                        </li>
                        <li class="faq-item" data-question="Can I track my order?">
                            <span class="faq-question">Can I track my order?</span>
                            <p class="faq-answer">Yes! Once your order is shipped, we will provide a tracking number via
                                email. You can use it to track your package online.</p>
                        </li>
                        <li class="faq-item" data-question="What are the shipping costs?">
                            <span class="faq-question">What are the shipping costs?</span>
                            <p class="faq-answer">Shipping costs depend on your location and the size of your order. You can
                                view the exact cost at checkout before confirming your order.</p>
                        </li>
                    </ul>
                </div>


                <div class="faq-category">
                    <h3><i class="fas fa-leaf"></i> Plant Care</h3>
                    <ul class="faq-list">
                        <li class="faq-item" data-question="How often should I water my plant?">
                            <span class="faq-question">How often should I water my plant?</span>
                            <p class="faq-answer">Water your plant 2-3 times per week or whenever the soil feels dry to the
                                touch. Adjust based on your plant type and environment.</p>
                        </li>
                        <li class="faq-item" data-question="What's the best light for my plant?">
                            <span class="faq-question">What's the best light for my plant?</span>
                            <p class="faq-answer">Most indoor plants thrive in bright, indirect sunlight. Avoid direct harsh
                                sunlight as it may burn the leaves.</p>
                        </li>
                        <li class="faq-item" data-question="Why are my plant's leaves turning yellow?">
                            <span class="faq-question">Why are my plant's leaves turning yellow?</span>
                            <p class="faq-answer">Yellow leaves can indicate overwatering, underwatering, or insufficient
                                light. Check your watering schedule and the plant’s location.</p>
                        </li>
                        <li class="faq-item" data-question="How do I repot my plant?">
                            <span class="faq-question">How do I repot my plant?</span>
                            <p class="faq-answer">Choose a pot slightly larger than the current one, use fresh soil, gently
                                remove the plant, place it in the new pot, and water lightly.</p>
                        </li>
                    </ul>
                </div>


                <div class="faq-category">
                    <h3><i class="fas fa-credit-card"></i> Payments & Returns</h3>
                    <ul class="faq-list">
                        <li class="faq-item" data-question="What payment methods do you accept?">
                            <span class="faq-question">What payment methods do you accept?</span>
                            <p class="faq-answer">We accept credit/debit cards, PayPal, and online banking payments. All
                                payment options are displayed at checkout.</p>
                        </li>
                        <li class="faq-item" data-question="How do I request a refund?">
                            <span class="faq-question">How do I request a refund?</span>
                            <p class="faq-answer">To request a refund, contact our support team via the “Contact Support”
                                button. Provide your order number and reason for the refund.</p>
                        </li>
                        <li class="faq-item" data-question="What's your return policy?">
                            <span class="faq-question">What's your return policy?</span>
                            <p class="faq-answer">You can return most plants within 7 days of delivery if they are damaged
                                or incorrect. Contact us to initiate a return.</p>
                        </li>
                        <li class="faq-item" data-question="My plant arrived damaged, what do I do?">
                            <span class="faq-question">My plant arrived damaged, what do I do?</span>
                            <p class="faq-answer">If your plant arrives damaged, please contact our support team immediately
                                with photos. We will arrange a replacement or refund.</p>
                        </li>
                    </ul>
                </div>

            </section>

            <!-- Contact Section -->
            <section class="contact-section">
                <h2 style="margin-bottom: 10px;">Still Need Help?</h2>
                <p style="margin-bottom: 20px; color: #666;">Our support team is here to assist you.</p>
                <div class="contact-buttons">
                    <a href="{{ route('complaints.create') }}" class="contact-btn">
                        <i class="fas fa-envelope"></i> Contact Support
                    </a>
                    <a href="{{ route('complaints.create') }}" class="contact-btn">
    <i class="fas fa-exclamation-circle"></i> File a Complaint
</a>


                </div>
            </section>
        </div>
    </div>

    <style>
        .help-center-container {
            padding: 20px 0 40px;
            min-height: 70vh;
        }

        /* Page Header */
        .page-header {
            text-align: center;
            padding: 30px 0;
            margin-bottom: 20px;
        }

        .page-header h1 {
            font-size: 2rem;
            color: #6A8F4E;
            margin-bottom: 10px;
        }

        .page-header p {
            color: #666;
        }

        /* Search Section */
        .search-section {
            background-color: #fff;
            border-radius: 10px;
            padding: 25px;
            margin: 20px auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            max-width: 800px;
        }

        .search-box {
            display: flex;
            gap: 10px;
        }

        .search-box input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .search-box button {
            background-color: #6A8F4E;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        .search-box button:hover {
            background-color: #5a7e3e;
        }

        /* Quick Help */
        .quick-help {
            margin: 30px 0;
        }

        .section-title {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #6A8F4E;
        }

        .help-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .help-card {
            background-color: #fff;
            padding: 25px 20px;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            color: #333;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }

        .help-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(106, 143, 78, 0.1);
            border-color: #6A8F4E;
            text-decoration: none;
            color: #333;
        }

        .help-card i {
            font-size: 2rem;
            color: #6A8F4E;
            margin-bottom: 15px;
        }

        .help-card h3 {
            font-size: 1.1rem;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .help-card p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }

        /* FAQ Section */
        .faq-section {
            margin: 40px 0;
        }

        .faq-category {
            background-color: #fff;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
            border-left: 4px solid #6A8F4E;
        }

        .faq-category h3 {
            color: #6A8F4E;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .faq-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .faq-list li {
            padding: 10px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .faq-list li:last-child {
            border-bottom: none;
        }

        .faq-list a {
            color: #333;
            text-decoration: none;
            display: block;
            padding: 5px 0;
            transition: color 0.2s;
        }

        .faq-list a:hover {
            color: #6A8F4E;
            text-decoration: none;
        }

        /* Contact Section */
        .contact-section {
            background-color: #f0f7e6;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin: 40px 0;
            border: 1px solid #e0e9d0;
        }

        .contact-section h2 {
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .contact-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .contact-btn {
            background-color: #6A8F4E;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s;
        }

        .contact-btn:hover {
            background-color: #5a7e3e;
            color: white;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .help-center-container {
                padding: 15px 0 30px;
            }

            .help-cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .search-box {
                flex-direction: column;
            }

            .search-box input,
            .search-box button {
                width: 100%;
            }

            .contact-buttons {
                flex-direction: column;
            }

            .contact-btn {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .help-cards {
                grid-template-columns: 1fr;
            }

            .page-header h1 {
                font-size: 1.6rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchBtn = document.getElementById('searchBtn');
            const searchInput = document.getElementById('searchInput');

            // "No results" message
            let noResults = document.createElement('p');
            noResults.textContent = "No questions found.";
            noResults.style.color = "#666";
            noResults.style.fontStyle = "italic";
            noResults.style.display = "none";
            searchInput.parentElement.parentElement.appendChild(noResults);

            function filterFAQs() {
                const term = searchInput.value.trim().toLowerCase();
                let anyVisible = false;

                document.querySelectorAll('.faq-category').forEach(category => {
                    let categoryHasVisible = false;

                    category.querySelectorAll('.faq-item').forEach(item => {
                        const question = item.dataset.question.toLowerCase();
                        if (question.includes(term)) {
                            item.style.display = 'block';
                            categoryHasVisible = true;
                            anyVisible = true;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Hide entire category if no items are visible
                    category.style.display = categoryHasVisible ? 'block' : 'none';
                });

                noResults.style.display = anyVisible ? 'none' : 'block';
            }

            // Search button click
            searchBtn.addEventListener('click', filterFAQs);

            // Enter key in input
            searchInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') filterFAQs();
            });

            // FAQ toggle for answers
            document.querySelectorAll('.faq-question').forEach(q => {
                q.addEventListener('click', function () {
                    const answer = this.nextElementSibling;
                    answer.style.display = (answer.style.display === 'block') ? 'none' : 'block';
                });
            });
        });
    </script>


@endsection