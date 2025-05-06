<?php
session_start();
date_default_timezone_set('Asia/Manila');

// Include database connection
require_once '../includes/db_connect.php';

// First, at the top of the file, add this code to handle form submissions:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    try {
        $reservation_id = $_POST['id'];
        $new_status = $_POST['status'];
        
        $update_stmt = $pdo->prepare("UPDATE reservations SET status = ?, updated_at = NOW() WHERE id = ?");
        $update_stmt->execute([$new_status, $reservation_id]);
        
        $_SESSION['success_message'] = "Reservation status updated successfully.";
        header("Location: view_reservation.php?id=$reservation_id");
        exit;
    } catch(PDOException $e) {
        $_SESSION['error_message'] = "Error updating status: " . $e->getMessage();
    }
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: reservations.php');
    exit;
}

$id = $_GET['id'];

// Get reservation details
try {
    $stmt = $pdo->prepare("SELECT * FROM reservations WHERE id = ?");
    $stmt->execute([$id]);
    $reservation = $stmt->fetch();
    
    if (!$reservation) {
        $_SESSION['error_message'] = "Reservation not found.";
        header('Location: reservations.php');
        exit;
    }
    
    // Calculate number of days if not already set
    if (!isset($reservation['days']) || empty($reservation['days'])) {
        $start_date = new DateTime($reservation['start_date']);
        $end_date = new DateTime($reservation['end_date']);
        $interval = $start_date->diff($end_date);
        $reservation['days'] = $interval->days;
    }
    
} catch(PDOException $e) {
    $_SESSION['error_message'] = "Error retrieving reservation: " . $e->getMessage();
    header('Location: reservations.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reservation - Purple Hotel Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Admin Header -->
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="container mx-auto px-4 py-8 flex-grow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-purple-800">Reservation Details</h1>
            
            <div class="space-x-2">
                <a href="reservations.php" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i> Back to List
                </a>
                <a href="edit_reservation.php?id=<?php echo $id; ?>" class="bg-purple-600 text-white py-2 px-4 rounded hover:bg-purple-700">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Status Badge -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Booking #<?php echo $reservation['booking_reference']; ?></h2>
                
                <?php
                $statusClass = '';
                switch ($reservation['status']) {
                    case 'pending':
                        $statusClass = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                        break;
                    case 'confirmed':
                        $statusClass = 'bg-green-100 text-green-800 border-green-200';
                        break;
                    case 'cancelled':
                        $statusClass = 'bg-red-100 text-red-800 border-red-200';
                        break;
                    case 'completed':
                        $statusClass = 'bg-blue-100 text-blue-800 border-blue-200';
                        break;
                }
                ?>
                <span class="px-3 py-1 rounded-full text-sm font-medium border <?php echo $statusClass; ?>">
                    <?php echo ucfirst($reservation['status']); ?>
                </span>
            </div>
            
            <!-- Reservation Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Guest Information -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-purple-800 mb-3 pb-2 border-b">Guest Information</h3>
                        <div class="space-y-3">
                            <div class="flex">
                                <span class="text-gray-600 w-32">Guest Name:</span>
                                <span class="font-medium"><?php echo $reservation['customer_name']; ?></span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 w-32">Contact Number:</span>
                                <span class="font-medium"><?php echo $reservation['contact_number']; ?></span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 w-32">Date Reserved:</span>
                                <span class="font-medium"><?php echo date('F d, Y', strtotime($reservation['date_reserved'])); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stay Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-purple-800 mb-3 pb-2 border-b">Stay Information</h3>
                        <div class="space-y-3">
                            <div class="flex">
                                <span class="text-gray-600 w-32">Check-in:</span>
                                <span class="font-medium"><?php echo date('F d, Y', strtotime($reservation['start_date'])); ?></span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 w-32">Check-out:</span>
                                <span class="font-medium"><?php echo date('F d, Y', strtotime($reservation['end_date'])); ?></span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 w-32">Number of Days:</span>
                                <span class="font-medium"><?php echo $reservation['days']; ?> day(s)</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Room Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-purple-800 mb-3 pb-2 border-b">Room Details</h3>
                        <div class="space-y-3">
                            <div class="flex">
                                <span class="text-gray-600 w-32">Room Type:</span>
                                <span class="font-medium capitalize"><?php echo $reservation['room_type']; ?></span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 w-32">Room Capacity:</span>
                                <span class="font-medium capitalize"><?php echo $reservation['room_capacity']; ?></span>
                            </div>
                            <div class="flex">
                                <span class="text-gray-600 w-32">Rate Per Day:</span>
                                <span class="font-medium">₱<?php echo number_format($reservation['rate_per_day'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-purple-800 mb-3 pb-2 border-b">Payment Information</h3>
                        <div class="space-y-3">
                            <div class="flex">
                                <span class="text-gray-600 w-32">Payment Type:</span>
                                <span class="font-medium capitalize"><?php echo $reservation['payment_type']; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Billing Summary -->
                    <div>
                        <h3 class="text-lg font-semibold text-purple-800 mb-3 pb-2 border-b">Billing Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal (<?php echo $reservation['days']; ?> days × ₱<?php echo number_format($reservation['rate_per_day'], 2); ?>):</span>
                                <span>₱<?php echo number_format($reservation['subtotal'], 2); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Additional Charges:</span>
                                <span>₱<?php echo number_format($reservation['additional_charge'], 2); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Discount:</span>
                                <span>-₱<?php echo number_format($reservation['discount'], 2); ?></span>
                            </div>
                            <div class="flex justify-between pt-3 border-t">
                                <span class="font-semibold">Total Amount:</span>
                                <span class="font-bold text-purple-800 text-lg">₱<?php echo number_format($reservation['total'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-purple-800 mb-3 pb-2 border-b">Actions</h3>
                        <div class="flex flex-wrap gap-2">
                            <form method="POST" class="inline-block">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="id" value="<?php echo $reservation['id']; ?>">
                                
                                <?php if ($reservation['status'] !== 'confirmed'): ?>
                                    <button type="submit" name="status" value="confirmed" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
                                        <i class="fas fa-check mr-2"></i> Confirm
                                    </button>
                                <?php endif; ?>
                                
                                <?php if ($reservation['status'] !== 'cancelled'): ?>
                                    <button type="submit" name="status" value="cancelled" class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700">
                                        <i class="fas fa-times mr-2"></i> Cancel
                                    </button>
                                <?php endif; ?>
                                
                                <?php if ($reservation['status'] !== 'completed'): ?>
                                    <button type="submit" name="status" value="completed" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                                        <i class="fas fa-check-double mr-2"></i> Mark as Completed
                                    </button>
                                <?php endif; ?>

                                <?php if ($reservation['status'] == 'confirmed'): ?>
                                    <button type="submit" name="status" value="checked_in" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">
                                        <i class="fas fa-sign-in-alt mr-2"></i> Check In
                                    </button>
                                <?php endif; ?>

                                <?php if ($reservation['status'] == 'checked_in'): ?>
                                    <button type="submit" name="status" value="completed" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                                        <i class="fas fa-check-double mr-2"></i> Check Out & Complete
                                    </button>
                                <?php endif; ?>
                            </form>
                            
                            <a href="print_voucher.php?id=<?php echo $reservation['id']; ?>" target="_blank" class="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700">
                                <i class="fas fa-print mr-2"></i> Print Voucher
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- System Information -->
            <div class="mt-8 pt-4 border-t text-xs text-gray-500">
                <p>Created: <?php echo date('F d, Y h:i A', strtotime($reservation['created_at'])); ?></p>
                <p>Last Updated: <?php echo !empty($reservation['updated_at']) && $reservation['updated_at'] != '0000-00-00 00:00:00' ? date('F d, Y h:i A', strtotime($reservation['updated_at'])) : date('F d, Y h:i A'); ?></p>
            </div>
        </div>
    </div>
    
    <!-- Admin Footer -->
    <?php include 'includes/admin_footer.php'; ?>
</body>
</html>