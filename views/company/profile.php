<?php
date_default_timezone_set('Asia/Manila'); // Set timezone to Philippines
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purple Hotel - About Us</title>
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
   

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <!-- Profile Section -->
            <div id="profile-section" class="content-section">
                <div class="customer-heading">Company Profile</div>
                <div class="profile-content space-y-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-purple-800">Welcome to Purple</h2>
                        <p class="text-gray-600 mt-2">Experience the Comfortable at it's Finest</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <h3 class="text-xl font-semibold text-purple-800">Our Legacy</h3>
                            </div>
                            <p class="text-gray-700">
                                Established in 2025, Purple Hotel has become synonymous with 
                                exceptional hospitality and luxurious accommodations. Our commitment to excellence 
                                has made us a preferred destination for both business and leisure travelers.
                            </p>
                        </div>

                        <div class="bg-purple-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                                <h3 class="text-xl font-semibold text-purple-800">Our Standards</h3>
                            </div>
                            <p class="text-gray-700">
                                We pride ourselves on maintaining the highest standards of service and comfort. 
                                Our rooms are meticulously maintained, and our staff is trained to provide 
                                personalized attention to each guest's needs.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-purple-800">Our Services</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-gray-50 rounded">
                                <h4 class="font-semibold text-purple-700">Accommodation</h4>
                                <ul class="mt-2 space-y-2 text-gray-600">
                                    <li>Regular Rooms</li>
                                    <li>Deluxe Rooms</li>
                                    <li>Luxury Suites</li>
                                </ul>
                            </div>
                            <div class="p-4 bg-gray-50 rounded">
                                <h4 class="font-semibold text-purple-700">Amenities</h4>
                                <ul class="mt-2 space-y-2 text-gray-600">
                                    <li>Swimming Pool</li>
                                    <li>Fitness Center</li>
                                    <li>Spa Services</li>
                                </ul>
                            </div>
                            <div class="p-4 bg-gray-50 rounded">
                                <h4 class="font-semibold text-purple-700">Dining</h4>
                                <ul class="mt-2 space-y-2 text-gray-600">
                                    <li>Fine Dining Restaurant</li>
                                    <li>Café & Bar</li>
                                    <li>Room Service</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Our Facilities -->
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <div class="flex items-center mb-4">
                            <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-purple-800">Our Location</h3>
                        </div>
                        <p class="text-gray-700 mb-4">
                            Purple Hotel is strategically located in Morcilla, Pateros, with easy access to major shopping centers, 
                            cultural sites, and business establishments in Metro Manila.
                        </p>
                    </div>
                    
                    <!-- Mission & Vision -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <h3 class="text-xl font-semibold text-purple-800">Our Mission</h3>
                            </div>
                            <p class="text-gray-700">
                                To provide extraordinary experiences through exceptional service, comfortable accommodations, 
                                and authentic Filipino hospitality, creating lasting memories for all our guests.
                            </p>
                        </div>

                        <div class="bg-purple-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <h3 class="text-xl font-semibold text-purple-800">Our Vision</h3>
                            </div>
                            <p class="text-gray-700">
                                To be recognized as the premier hotel destination in the Philippines, setting the standard for 
                                hospitality excellence and becoming the first choice for discerning travelers.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

</body>
</html>