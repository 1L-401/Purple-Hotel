<?php
session_start();
date_default_timezone_set('Asia/Manila'); // Set timezone to Philippines

if (empty($_SESSION['bill'])) {
    header('Location: index.php');
    exit;
}

$bill = $_SESSION['bill'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purple Hotel - Billing</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation Menu -->
    <?php include 'includes/nav.php'; ?>

    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-2xl font-bold text-center mb-8 text-purple-800">PURPLE HOTEL</h1>
            <h2 class="text-xl text-center mb-6">BILLING INFORMATION</h2>

            <!-- CUSTOMER INFORMATION SECTION -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Customer Information</h3>
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
                        <p class="text-sm font-medium text-gray-500">Date Reserved:</p>
                        <p class="mt-1 font-semibold"><?php echo htmlspecialchars($bill['dateReserved']); ?></p>
                    </div>
                </div>
            </div>

            <!--RESERVATION DETAILS SECTION -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Reservation Details</h3>
                <div class="grid grid-cols-2 gap-4 mt-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Reservation From:</p>
                        <p class="mt-1 font-semibold"><?php echo htmlspecialchars($bill['startDate']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Reservation To:</p>
                        <p class="mt-1 font-semibold"><?php echo htmlspecialchars($bill['endDate']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Number of Days:</p>
                        <p class="mt-1 font-semibold"><?php echo $bill['days']; ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Room Type:</p>
                        <p class="mt-1 font-semibold">
                            <?php 
                            $roomTypes = [
                                'deluxe' => 'Deluxe Room',
                                'executive' => 'Executive Suite',
                                'presidential' => 'Presidential Suite'
                            ];
                            echo $roomTypes[$bill['roomType']] ?? ''; 
                            ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Rate Per Day:</p>
                        <p class="mt-1 font-semibold">₱<?php echo number_format($bill['ratePerDay'], 2); ?></p>
                    </div>
                </div>
            </div>

            <!-- BILLING DETAILS SECTION -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Billing Details</h3>
                <div class="grid grid-cols-2 gap-4 mt-3">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Sub-Total:</p>
                        <p class="mt-1 font-semibold">₱<?php echo number_format($bill['subtotal'], 2); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Additional Charge:</p>
                        <p class="mt-1 font-semibold">₱<?php echo number_format($bill['additionalCharge'], 2); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Discount:</p>
                        <p class="mt-1 font-semibold">₱<?php echo number_format($bill['discount'], 2); ?></p>
                    </div>
                    <div class="col-span-2 text-right">
                        <p class="text-lg font-bold">Total Bill: ₱<?php echo number_format($bill['total'], 2); ?></p>
                    </div>
                </div>
            </div>

            <!-- BILLING SUBMISSION TIME -->
            <div class="text-center mt-4">
                <p class="text-sm font-medium text-gray-500">Billing Time Submitted:</p>
                <p class="mt-1 text-gray-700 font-semibold">
                    <?php echo isset($bill['submittedTime']) ? $bill['submittedTime'] : 'Not Available'; ?>
                </p>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex justify-between mt-8">
                <a href="reservation.php" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    Back to Reservation
                </a>
                <a href="confirmation.php" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                    Confirm Booking
                </a>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>
</body>
</html>