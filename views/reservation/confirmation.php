<?php
date_default_timezone_set('Asia/Manila'); // Set timezone to Philippines

if (empty($_SESSION['bill'])) {
    header('Location: index.php');
    exit;
}

$bill = $_SESSION['bill'];

// Generate a booking reference number
$bookingReference = 'PH' . date('Ymd') . rand(1000, 9999);

// In a real application, you would save the booking to a database here
// For this example, we'll just add the reference to the session
$_SESSION['bill']['bookingReference'] = $bookingReference;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purple Hotel - Booking Confirmation</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-2xl font-bold text-center mb-8 text-purple-800">PURPLE HOTEL</h1>
            <h2 class="text-xl text-center mb-2">BOOKING CONFIRMATION</h2>
            
            <!-- Success Message -->
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p class="font-bold">Booking Successful!</p>
                <p>Your reservation has been confirmed. Please save your booking reference for future inquiries.</p>
            </div>
            
            <!-- Booking Reference -->
            <div class="bg-purple-50 p-4 text-center mb-6 rounded-lg border border-purple-200">
                <p class="text-sm font-medium text-purple-700">Booking Reference:</p>
                <p class="text-2xl font-bold text-purple-800"><?php echo $bookingReference; ?></p>
            </div>
            
            <!-- Booking Summary -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Booking Summary</h3>
                <div class="grid grid-cols-2 gap-4 mt-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Customer Name:</p>
                        <p class="mt-1 font-semibold"><?php echo htmlspecialchars($bill['customerName']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Contact Number:</p>
                        <p class="mt-1 font-semibold"><?php echo htmlspecialchars($bill['contactNumber']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Room Capacity:</p>
                        <p class="mt-1 font-semibold">
                            <?php 
                            $capacities = [
                                'single' => 'Single',
                                'double' => 'Double', 
                                'family' => 'Family'
                            ];
                            echo $capacities[$bill['roomCapacity']] ?? ucfirst($bill['roomCapacity']); 
                            ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Room Type:</p>
                        <p class="mt-1 font-semibold">
                            <?php 
                            $roomTypes = [
                                'regular' => 'Regular Room',
                                'deluxe' => 'De Luxe Room',
                                'suite' => 'Suite'
                            ];
                            echo $roomTypes[$bill['roomType']] ?? ucfirst($bill['roomType']); 
                            ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Payment Type:</p>
                        <p class="mt-1 font-semibold">
                            <?php 
                            $paymentTypes = [
                                'cash' => 'Cash',
                                'check' => 'Check',
                                'credit' => 'Credit Card'
                            ];
                            echo $paymentTypes[$bill['paymentType']] ?? ucfirst($bill['paymentType']); 
                            ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Rate Per Day:</p>
                        <p class="mt-1 font-semibold">₱<?php echo number_format($bill['ratePerDay'], 2); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Check-in Date:</p>
                        <p class="mt-1 font-semibold"><?php echo date('F d, Y', strtotime($bill['startDate'])); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Check-out Date:</p>
                        <p class="mt-1 font-semibold"><?php echo date('F d, Y', strtotime($bill['endDate'])); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Number of Nights:</p>
                        <p class="mt-1 font-semibold"><?php echo $bill['days']; ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Payment Breakdown -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Payment Breakdown</h3>
                <div class="mt-3 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal (<?php echo $bill['days']; ?> nights × ₱<?php echo number_format($bill['ratePerDay'], 2); ?>):</span>
                        <span class="font-medium">₱<?php echo number_format($bill['subtotal'], 2); ?></span>
                    </div>
                    
                    <?php if ($bill['additionalCharge'] > 0): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Additional Charge (<?php echo $bill['paymentType'] === 'check' ? '5%' : '10%'; ?>):</span>
                        <span class="font-medium">₱<?php echo number_format($bill['additionalCharge'], 2); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($bill['discount'] > 0): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Discount (<?php echo ($bill['days'] >= 3 && $bill['days'] <= 5) ? '10%' : '15%'; ?>):</span>
                        <span class="font-medium text-green-600">-₱<?php echo number_format($bill['discount'], 2); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="flex justify-between pt-2 border-t border-gray-200">
                        <span class="font-bold">Total Amount:</span>
                        <span class="font-bold">₱<?php echo number_format($bill['total'], 2); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Important Information -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Important Information</h3>
                <ul class="list-disc list-inside mt-3 text-gray-700 space-y-2">
                    <li>Check-in time is at 2:00 PM and check-out time is at 12:00 PM.</li>
                    <li>Please present your booking reference and a valid ID upon check-in.</li>
                    <li>Free cancellation is available up to 48 hours before your check-in date.</li>
                    <li>For any changes or inquiries about your booking, please contact our reservations department.</li>
                </ul>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-center space-x-4 mt-8">
                <button onclick="window.print()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Print Confirmation
                </button>
                <a href="index.php" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700">
                    Return to Homepage
                </a>
            </div>
        </div>
    </div>
</body>
</html>