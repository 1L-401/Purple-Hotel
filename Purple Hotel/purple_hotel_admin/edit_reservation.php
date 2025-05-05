<?php
session_start();
date_default_timezone_set('Asia/Manila');

// Check if user is logged in


// Include database connection
require_once '../includes/db_connect.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: reservations.php');
    exit;
}

$id = $_GET['id'];
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_reservation'])) {
    // Collect form data
    $customerName = $_POST['customerName'] ?? '';
    $contactNumber = $_POST['contactNumber'] ?? '';
    $startDate = $_POST['startDate'] ?? '';
    $endDate = $_POST['endDate'] ?? '';
    $roomType = $_POST['roomType'] ?? '';
    $roomCapacity = $_POST['roomCapacity'] ?? '';
    $paymentType = $_POST['paymentType'] ?? '';
    $status = $_POST['status'] ?? 'pending';
    
    // Calculate number of days
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $interval = $start->diff($end);
    $days = $interval->days;
    
    // Get room rate from the database
    try {
        $stmt = $pdo->prepare("SELECT rate FROM room_types WHERE name = ?");
        $stmt->execute([$roomType]);
        $room = $stmt->fetch();
        $ratePerDay = $room ? $room['rate'] : 0;
    } catch(PDOException $e) {
        // Fallback if database query fails
        $roomRates = [
            'deluxe' => 3500,
            'executive' => 5200,
            'presidential' => 8000,
            'regular' => 2500,
            'suite' => 6500
        ];
        $ratePerDay = $roomRates[$roomType] ?? 0;
    }
    
    $subtotal = $ratePerDay * $days;
    
    // Calculate additional charge and discount based on payment type
    $additionalCharge = $subtotal * 0.10;
    $discount = $subtotal * 0.05;
    
    $total = $subtotal + $additionalCharge - $discount;
    
    // Update the reservation in the database
    try {
        $sql = "UPDATE reservations SET 
                customer_name = :customer_name,
                contact_number = :contact_number,
                start_date = :start_date,
                end_date = :end_date,
                room_type = :room_type,
                room_capacity = :room_capacity,
                payment_type = :payment_type,
                rate_per_day = :rate_per_day,
                subtotal = :subtotal,
                additional_charge = :additional_charge,
                discount = :discount,
                total = :total,
                status = :status,
                updated_at = NOW()
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':customer_name' => $customerName,
            ':contact_number' => $contactNumber,
            ':start_date' => $startDate,
            ':end_date' => $endDate,
            ':room_type' => $roomType,
            ':room_capacity' => $roomCapacity,
            ':payment_type' => $paymentType,
            ':rate_per_day' => $ratePerDay,
            ':subtotal' => $subtotal,
            ':additional_charge' => $additionalCharge,
            ':discount' => $discount,
            ':total' => $total,
            ':status' => $status,
            ':id' => $id
        ]);
        
        $success = 'Reservation updated successfully!';
    } catch(PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}

// Get reservation data for display
try {
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ?");
    $stmt->execute([$id]);
    $reservation = $stmt->fetch();
    
    if (!$reservation) {
        header('Location: reservations.php');
        exit;
    }
} catch(PDOException $e) {
    $error = 'Database error: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservation - Purple Hotel Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="container mx-auto px-4 py-8 flex-grow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Reservation</h1>
            <a href="reservations.php" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">
                Back to Reservations
            </a>
        </div>
        
        <?php if ($error): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p><?php echo $error; ?></p>
        </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p><?php echo $success; ?></p>
        </div>
        <?php endif; ?>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="border-b border-gray-200 pb-4 mb-6">
                <h2 class="text-lg font-medium">Booking Reference: <span class="font-bold"><?php echo htmlspecialchars($reservation['booking_reference']); ?></span></h2>
                <p class="text-sm text-gray-600">Reserved on: <?php echo date('F d, Y', strtotime($reservation['date_reserved'])); ?></p>
            </div>
            
            <form method="POST" class="space-y-6">
                <!-- Customer Information -->
                <div>
                    <h3 class="text-lg font-medium border-b pb-2 mb-4">Customer Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="customerName" class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                            <input type="text" id="customerName" name="customerName" value="<?php echo htmlspecialchars($reservation['customer_name']); ?>" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="contactNumber" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                            <input type="tel" id="contactNumber" name="contactNumber" value="<?php echo htmlspecialchars($reservation['contact_number']); ?>" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>
                
                <!-- Stay Duration -->
                <div>
                    <h3 class="text-lg font-medium border-b pb-2 mb-4">Stay Duration</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="startDate" class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                            <input type="date" id="startDate" name="startDate" value="<?php echo htmlspecialchars($reservation['start_date']); ?>" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="endDate" class="block text-sm font-medium text-gray-700 mb-1">Check-out Date</label>
                            <input type="date" id="endDate" name="endDate" value="<?php echo htmlspecialchars($reservation['end_date']); ?>" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>
                
                <!-- Room Details & Payment -->
                <div>
                    <h3 class="text-lg font-medium border-b pb-2 mb-4">Room Details & Payment</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="roomType" class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                            <select id="roomType" name="roomType" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                <option value="">Select Room Type</option>
                                <option value="regular" <?php echo $reservation['room_type'] === 'regular' ? 'selected' : ''; ?>>Regular - ₱2,500 per night</option>
                                <option value="deluxe" <?php echo $reservation['room_type'] === 'deluxe' ? 'selected' : ''; ?>>Deluxe - ₱3,500 per night</option>
                                <option value="executive" <?php echo $reservation['room_type'] === 'executive' ? 'selected' : ''; ?>>Executive - ₱5,200 per night</option>
                                <option value="suite" <?php echo $reservation['room_type'] === 'suite' ? 'selected' : ''; ?>>Suite - ₱6,500 per night</option>
                                <option value="presidential" <?php echo $reservation['room_type'] === 'presidential' ? 'selected' : ''; ?>>Presidential - ₱8,000 per night</option>
                            </select>
                        </div>
                        <div>
                            <label for="roomCapacity" class="block text-sm font-medium text-gray-700 mb-1">Room Capacity</label>
                            <select id="roomCapacity" name="roomCapacity" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                <option value="">Select Capacity</option>
                                <option value="single" <?php echo $reservation['room_capacity'] === 'single' ? 'selected' : ''; ?>>Single</option>
                                <option value="double" <?php echo $reservation['room_capacity'] === 'double' ? 'selected' : ''; ?>>Double</option>
                                <option value="family" <?php echo $reservation['room_capacity'] === 'family' ? 'selected' : ''; ?>>Family</option>
                            </select>
                        </div>
                        <div>
                            <label for="paymentType" class="block text-sm font-medium text-gray-700 mb-1">Payment Type</label>
                            <select id="paymentType" name="paymentType" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                                <option value="">Select Payment Type</option>
                                <option value="cash" <?php echo $reservation['payment_type'] === 'cash' ? 'selected' : ''; ?>>Cash</option>
                                <option value="cheque" <?php echo $reservation['payment_type'] === 'cheque' ? 'selected' : ''; ?>>Cheque</option>
                                <option value="credit" <?php echo $reservation['payment_type'] === 'credit' ? 'selected' : ''; ?>>Credit Card</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Reservation Status -->
                <div>
                    <h3 class="text-lg font-medium border-b pb-2 mb-4">Reservation Status</h3>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status" required
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-500 focus:ring-opacity-50">
                            <option value="pending" <?php echo $reservation['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="confirmed" <?php echo $reservation['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="checked_in" <?php echo $reservation['status'] === 'checked_in' ? 'selected' : ''; ?>>Checked In</option>
                            <option value="checked_out" <?php echo $reservation['status'] === 'checked_out' ? 'selected' : ''; ?>>Checked Out</option>
                            <option value="cancelled" <?php echo $reservation['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                </div>
                
                <!-- Pricing Summary -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">Pricing Summary</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Rate per day:</span>
                            <span class="font-medium">₱<?php echo number_format($reservation['rate_per_day'], 2); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Number of days:</span>
                            <span class="font-medium"><?php echo $interval->days ?? 0; ?> days</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium">₱<?php echo number_format($reservation['subtotal'], 2); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Additional charge:</span>
                            <span class="font-medium">₱<?php echo number_format($reservation['additional_charge'], 2); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Discount:</span>
                            <span class="font-medium">₱<?php echo number_format($reservation['discount'], 2); ?></span>
                        </div>
                        <div class="flex justify-between border-t pt-2 mt-2">
                            <span class="text-gray-800 font-bold">Total:</span>
                            <span class="text-purple-700 font-bold">₱<?php echo number_format($reservation['total'], 2); ?></span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-4">
                        Note: The pricing will be automatically recalculated based on the updated dates and room type.
                    </p>
                </div>
                
                <div class="flex justify-end space-x-4 pt-4 border-t">
                    <a href="reservations.php" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-100">
                        Cancel
                    </a>
                    <button type="submit" name="update_reservation" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                        Update Reservation
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <?php include 'includes/admin_footer.php'; ?>
    
    <script>
        // Client-side validation and price calculation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');
            const roomTypeSelect = document.getElementById('roomType');
            
            form.addEventListener('submit', function(event) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                
                if (endDate <= startDate) {
                    event.preventDefault();
                    alert('Check-out date must be after check-in date');
                }
            });
            
            // You could add more advanced price calculation here if needed
        });
    </script>
</body>
</html>