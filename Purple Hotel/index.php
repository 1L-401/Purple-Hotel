<?php
session_start();
date_default_timezone_set('Asia/Manila'); // Set timezone to Philippines
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purple Hotel - Home</title>
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
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation Menu -->
    <?php include 'includes/nav.php'; ?>

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <!-- Home Section -->
            <div id="home-section" class="content-section">
                <div class="customer-heading">Home</div>
                <div class="profile-content space-y-8">
                    <!-- Hero Section -->
                    <div class="relative rounded-lg overflow-hidden h-64 mb-8" style="background-image: url('assets/images/hotel-bg.jpg'); background-size: cover; background-position: center;">
                        <div class="absolute inset-0 bg-purple-900 bg-opacity-60 flex items-center justify-center">
                            <div class="text-center text-white">
                                <h1 class="text-4xl font-bold mb-2">Welcome to Purple</h1>
                                <p class="text-xl">Experience the Comfortable at it's Finest</p>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Amenities -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-purple-50 rounded-lg p-6 text-center">
                            <svg class="w-12 h-12 text-purple-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-purple-800 mb-2">24/7 Service</h3>
                            <p class="text-gray-600">All day Room service for our customer's convenience</p>
                        </div>

                        <div class="bg-purple-50 rounded-lg p-6 text-center">
                            <svg class="w-12 h-12 text-purple-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-purple-800 mb-2">Location</h3>
                            <p class="text-gray-600">Located at Morcilla Pateros</p>
                        </div>

                        <div class="bg-purple-50 rounded-lg p-6 text-center">
                            <svg class="w-12 h-12 text-purple-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-purple-800 mb-2">Fast Wi-Fi</h3>
                            <p class="text-gray-600">High-speed internet access throughout the property</p>
                        </div>
                    </div>

                    <!-- Room Showcase -->
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-2xl font-bold text-purple-800 mb-6">Our Rooms</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-purple-50 rounded-lg p-6 text-center">
                                <h3 class="text-xl font-semibold text-purple-800 mb-2">Regular Room</h3>
                                <p class="text-gray-600 mb-4">Comfortable and cozy accommodations for the budget-conscious traveler</p>
                                <p class="text-purple-600 font-semibold">Starting at ₱100/Day</p>
                            </div>

                            <div class="bg-purple-50 rounded-lg p-6 text-center">
                                <h3 class="text-xl font-semibold text-purple-800 mb-2">Deluxe Room</h3>
                                <p class="text-gray-600 mb-4">Upgraded amenities and extra space for a more relaxing stay</p>
                                <p class="text-purple-600 font-semibold">Starting at ₱500/Day</p>
                            </div>

                            <div class="bg-purple-50 rounded-lg p-6 text-center">
                                <h3 class="text-xl font-semibold text-purple-800 mb-2">Suite Room</h3>
                                <p class="text-gray-600 mb-4">Ultimate luxury with separate living area and premium services</p>
                                <p class="text-purple-600 font-semibold">Starting at ₱500/Day</p>
                            </div>
                        </div>
                    </div>

                    <!-- Special Offers -->
                    <div class="bg-purple-800 text-white p-6 rounded-lg">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                            </svg>
                            <h2 class="text-2xl font-bold">Special Offers</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-purple-700 p-4 rounded-lg">
                                <h3 class="font-semibold mb-2">Cash Payment Discount</h3>
                                <p>Experience up to 15% discount for Cash payment in 3 days above reservation</p>
                            </div>
                            <div class="bg-purple-700 p-4 rounded-lg">
                                <h3 class="font-semibold mb-2">Budget Offers</h3>
                                <p>Get the services at affordable rates, suitable for individuals and families alike</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <a href="reservation.php" class="bg-purple-600 text-white p-6 rounded-lg hover:bg-purple-700 transition duration-300">
                            <h3 class="text-xl font-semibold mb-2">Make a Reservation</h3>
                            <p>Book your stay directly for the best rates and perks</p>
                        </a>
                        <a href="contact.php" class="bg-purple-100 text-purple-800 p-6 rounded-lg hover:bg-purple-200 transition duration-300">
                            <h3 class="text-xl font-semibold mb-2">Contact Us</h3>
                            <p>Need help? Our team is available 24/7 to assist you</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
        // Add a placeholder background if the image doesn't load
        document.addEventListener('DOMContentLoaded', function() {
            const heroSection = document.querySelector('.relative.rounded-lg.overflow-hidden.h-64');
            heroSection.style.backgroundColor = '#8b5cf6';
        });
    </script>
</body>
</html>