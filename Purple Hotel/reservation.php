<?php
session_start();
date_default_timezone_set('Asia/Manila'); // Set timezone to Philippines

// Include database connection
require_once 'includes/db_connect.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reservation'])) {
    // Collect form data
    $customerName = $_POST['customerName'] ?? '';
    $contactNumber = $_POST['contactNumber'] ?? '';
    $startDate = $_POST['startDate'] ?? '';
    $endDate = $_POST['endDate'] ?? '';
    $roomType = $_POST['roomType'] ?? '';
    $roomCapacity = $_POST['roomCapacity'] ?? '';
    $paymentType = $_POST['paymentType'] ?? '';
    
    // Calculate number of days
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $interval = $start->diff($end);
    $days = $interval->days;
    
    // Room rates based on capacity and type (from the provided table)
    $roomRates = [
        'single' => [
            'regular' => 100.00,
            'deluxe' => 300.00,
            'suite' => 500.00
        ],
        'double' => [
            'regular' => 200.00,
            'deluxe' => 500.00,
            'suite' => 800.00
        ],
        'family' => [
            'regular' => 500.00,
            'deluxe' => 750.00,
            'suite' => 1000.00
        ]
    ];
    
    // Get room rate based on capacity and type
    $ratePerDay = $roomRates[$roomCapacity][$roomType] ?? 0;
    $subtotal = $ratePerDay * $days;
    
    // Additional charge based on payment type
    $additionalChargeRate = 0;
    if ($paymentType === 'credit') {
        $additionalChargeRate = 0.10; // 10% for credit card
    } elseif ($paymentType === 'check') {
        $additionalChargeRate = 0.05; // 5% for check
    }
    $additionalCharge = $subtotal * $additionalChargeRate;
    
    // Calculate discount based on stay duration
    $discountRate = 0;
    if ($days >= 3 && $days <= 5) {
        $discountRate = 0.10; // 10% discount for 3-5 days
    } elseif ($days >= 6) {
        $discountRate = 0.15; // 15% discount for 6+ days
    }
    $discount = $subtotal * $discountRate;
    
    $total = $subtotal + $additionalCharge - $discount;
    
    // Generate a booking reference number
    $bookingReference = 'PH' . date('Ymd') . rand(1000, 9999);
    
    // Store in session (for the billing page)
    $_SESSION['bill'] = [
        'bookingReference' => $bookingReference,
        'customerName' => $customerName,
        'contactNumber' => $contactNumber,
        'dateReserved' => date('Y-m-d'),
        'startDate' => $startDate,
        'endDate' => $endDate,
        'days' => $days,
        'roomType' => $roomType,
        'roomCapacity' => $roomCapacity,
        'paymentType' => $paymentType,
        'ratePerDay' => $ratePerDay,
        'subtotal' => $subtotal,
        'additionalCharge' => $additionalCharge,
        'discount' => $discount,
        'total' => $total,
        'submittedTime' => date('Y-m-d H:i:s')
    ];
    
    // Save to database
    try {
        $sql = "INSERT INTO reservations (
                booking_reference, customer_name, contact_number, date_reserved, 
                start_date, end_date, room_type, room_capacity, payment_type, 
                rate_per_day, subtotal, additional_charge, discount, total, status
            ) VALUES (
                :booking_reference, :customer_name, :contact_number, :date_reserved, 
                :start_date, :end_date, :room_type, :room_capacity, :payment_type, 
                :rate_per_day, :subtotal, :additional_charge, :discount, :total, 'pending'
            )";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':booking_reference' => $bookingReference,
            ':customer_name' => $customerName,
            ':contact_number' => $contactNumber,
            ':date_reserved' => date('Y-m-d'),
            ':start_date' => $startDate,
            ':end_date' => $endDate,
            ':room_type' => $roomType,
            ':room_capacity' => $roomCapacity,
            ':payment_type' => $paymentType,
            ':rate_per_day' => $ratePerDay,
            ':subtotal' => $subtotal,
            ':additional_charge' => $additionalCharge,
            ':discount' => $discount,
            ':total' => $total
        ]);
        
        // Redirect to billing page
        header('Location: billing.php');
        exit;
    } catch(PDOException $e) {
        // Handle database error
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        // Still redirect to billing page as we have the bill in session
        header('Location: billing.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purple Hotel - Reservation</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        .customer-heading {
            background-color: #6b21a8; /* Updated to a darker purple shade */
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
            <!-- Reservation Section -->
            <div id="reservation-section" class="content-section">
                <div class="customer-heading">Reservation</div>
                <div class="profile-content space-y-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-purple-800">Book Your Stay</h2>
                        <p class="text-gray-600 mt-2">Experience the Comfortable at it's Finest</p>
                    </div>

                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="space-y-8">
                        <!-- Personal Information Section -->
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <h3 class="text-xl font-semibold text-purple-800">Guest Information</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="customerName" class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                                    <input type="text" name="customerName" id="customerName" required 
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="contactNumber" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                                    <input type="tel" name="contactNumber" id="contactNumber" required 
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <!-- Stay Duration Section -->
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="text-xl font-semibold text-purple-800">Stay Duration</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="startDate" class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                                    <input type="date" name="startDate" id="startDate" required 
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="endDate" class="block text-sm font-medium text-gray-700 mb-1">Check-out Date</label>
                                    <input type="date" name="endDate" id="endDate" required 
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <!-- Room and Payment Section -->
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <h3 class="text-xl font-semibold text-purple-800">Room Details & Payment</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="roomCapacity" class="block text-sm font-medium text-gray-700 mb-1">Room Capacity</label>
                                    <select name="roomCapacity" id="roomCapacity" required 
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                                        <option value="">Select...</option>
                                        <option value="single">Single</option>
                                        <option value="double">Double</option>
                                        <option value="family">Family</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="roomType" class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                                    <select name="roomType" id="roomType" required 
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                                        <option value="">Select...</option>
                                        <option value="regular">Regular</option>
                                        <option value="deluxe">De Luxe</option>
                                        <option value="suite">Suite</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="paymentType" class="block text-sm font-medium text-gray-700 mb-1">Payment Type</label>
                                    <select name="paymentType" id="paymentType" required 
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                                        <option value="">Select...</option>
                                        <option value="cash">Cash</option>
                                        <option value="check">Check</option>
                                        <option value="credit">Credit Card</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Section -->
                        <div class="flex justify-end space-x-4">
                            <button type="reset" 
                                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2">
                                Clear Entry
                            </button>
                            <button type="submit" name="submit_reservation"
                                class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2">
                                Submit Reservation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
        // Client-side validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const roomCapacitySelect = document.getElementById('roomCapacity');
            const roomTypeSelect = document.getElementById('roomType');
            
            // Display current rates when options are selected
            function updateRateDisplay() {
                const capacity = roomCapacitySelect.value;
                const type = roomTypeSelect.value;
                
                // Room rate mapping (same as in PHP)
                const roomRates = {
                    'single': {
                        'regular': 100.00,
                        'deluxe': 300.00,
                        'suite': 500.00
                    },
                    'double': {
                        'regular': 200.00,
                        'deluxe': 500.00,
                        'suite': 800.00
                    },
                    'family': {
                        'regular': 500.00,
                        'deluxe': 750.00,
                        'suite': 1000.00
                    }
                };
                
                // Show rate if both selections are made
                if (capacity && type && roomRates[capacity] && roomRates[capacity][type]) {
                    const rate = roomRates[capacity][type];
                    // You could display this somewhere on the page if needed
                    console.log(`Selected rate: ${rate} per day`);
                }
            }
            
            roomCapacitySelect.addEventListener('change', updateRateDisplay);
            roomTypeSelect.addEventListener('change', updateRateDisplay);
            
            form.addEventListener('submit', function(event) {
                const startDate = new Date(document.getElementById('startDate').value);
                const endDate = new Date(document.getElementById('endDate').value);
                
                if (endDate <= startDate) {
                    event.preventDefault();
                    alert('Check-out date must be after check-in date');
                }
            });
        });
    </script>
</body>
</html>