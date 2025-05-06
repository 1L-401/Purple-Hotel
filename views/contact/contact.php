<?php
date_default_timezone_set('Asia/Manila'); // Set timezone to Philippines

// Process form submission
$messageSent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    // In a real application, you would process the form data here
    // For example, send an email or store in database
    $messageSent = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purple Hotel - Contact Us</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        .customer-heading {
            background-color: #6b21a8; /* Dark purple shade */
            color: white;
            padding: 15px;
            margin: -24px -24px 24px -24px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .content-section {
            opacity: 1;
            display: block;
        }
        .profile-content {
            padding: 20px;
        }
        .email-text {
            word-break: break-all;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <div id="contacts-section" class="content-section">
                <div class="customer-heading">Contacts</div>
                <div class="profile-content space-y-8">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-purple-800">Get in Touch</h2>
                        <p class="text-gray-600 mt-2">We're here to help make your stay perfect</p>
                    </div>

                    <?php if ($messageSent): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Message Sent!</p>
                        <p>Thank you for contacting us. We will get back to you shortly.</p>
                    </div>
                    <?php endif; ?>

                    <!-- Contact Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Front Desk -->
                        <div class="bg-purple-50 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-purple-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <h3 class="text-xl font-semibold text-purple-800">Front Desk</h3>
                            </div>
                            <div class="space-y-3">
                                <p class="text-gray-600">Available 24/7</p>
                                <p class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span>0908170565</span>
                                </p>
                                <p class="flex items-start">
                                    <svg class="w-5 h-5 text-purple-600 mr-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="email-text">front@purplehotel.com</span>
                                </p>
                            </div>
                        </div>

                        <!-- Reservations -->
                        <div class="bg-purple-50 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-purple-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="text-xl font-semibold text-purple-800">Reservations</h3>
                            </div>
                            <div class="space-y-3">
                                <p class="text-gray-600">8:00 AM - 8:00 PM</p>
                                <p class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span>0909876543</span>
                                </p>
                                <p class="flex items-start">
                                    <svg class="w-5 h-5 text-purple-600 mr-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="email-text">book@purplehotel.com</span>
                                </p>
                            </div>
                        </div>

                        <!-- Customer Service -->
                        <div class="bg-purple-50 rounded-lg p-6">
                            <div class="flex items-center mb-4">
                                <svg class="w-8 h-8 text-purple-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <h3 class="text-xl font-semibold text-purple-800">Customer Service</h3>
                            </div>
                            <div class="space-y-3">
                                <p class="text-gray-600">9:00 AM - 6:00 PM</p>
                                <p class="flex items-center">
                                    <svg class="w-5 h-5 text-purple-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span>0912345678</span>
                                </p>
                                <p class="flex items-start">
                                    <svg class="w-5 h-5 text-purple-600 mr-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="email-text">help@purplehotel.com</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Address (No Directions) -->
                    <div class="bg-purple-50 rounded-lg p-6 mt-6">
                        <div class="flex items-center mb-4">
                            <svg class="w-8 h-8 text-purple-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-purple-800">Our Location</h3>
                        </div>
                        <div>
                            <p class="text-gray-700 mb-2">
                                <strong>Address:</strong>
                            </p>
                            <p class="text-gray-600">123 Manila Avenue, Makati City, Metro Manila, Philippines</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>