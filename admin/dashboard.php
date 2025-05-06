<?php
// filepath: c:\xampp\htdocs\Purple Hotel\admin\index.php
session_start();
date_default_timezone_set('Asia/Manila');

// Include database connection
require_once '../includes/db_connect.php';

// Get counts for dashboard
try {
    // Total reservations
    $stmt = $pdo->query("SELECT COUNT(*) FROM reservations");
    $totalReservations = $stmt->fetchColumn();
    
    // Pending reservations
    $stmt = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'pending'");
    $pendingReservations = $stmt->fetchColumn();
    
    // Today's reservations
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE date_reserved = ?");
    $stmt->execute([date('Y-m-d')]);
    $todayReservations = $stmt->fetchColumn();
    
    // Total revenue
    $stmt = $pdo->query("SELECT SUM(total) FROM reservations WHERE status != 'cancelled'");
    $totalRevenue = $stmt->fetchColumn() ?? 0;
    
    // Get recent reservations
    $stmt = $pdo->query("SELECT * FROM reservations ORDER BY id DESC LIMIT 5");
    $recentReservations = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
    // Initialize variables to prevent undefined variable errors
    $totalReservations = 0;
    $pendingReservations = 0;
    $todayReservations = 0;
    $totalRevenue = 0;
    $recentReservations = [];
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Purple Hotel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <?php include 'includes/admin_header.php'; ?>

    <div class="container mx-auto px-4 py-8 flex-grow">
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-800">
                        <i class="fas fa-calendar-check text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Reservations</p>
                        <p class="text-2xl font-semibold"><?php echo $totalReservations; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Pending Reservations</p>
                        <p class="text-2xl font-semibold"><?php echo $pendingReservations; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-calendar-day text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Today's Reservations</p>
                        <p class="text-2xl font-semibold"><?php echo $todayReservations; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-800">
                        <i class="fas fa-money-bill-wave text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total Revenue</p>
                        <p class="text-2xl font-semibold">₱<?php echo number_format($totalRevenue, 2); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-purple-800 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="reservations.php" class="bg-purple-600 text-white py-3 px-4 rounded-lg text-center hover:bg-purple-700 transition duration-200">
                    <i class="fas fa-list-alt mr-2"></i> View All Reservations
                </a>
                <a href="reservations.php?status=pending" class="bg-yellow-500 text-white py-3 px-4 rounded-lg text-center hover:bg-yellow-600 transition duration-200">
                    <i class="fas fa-clock mr-2"></i> Manage Pending Reservations
                </a>
            </div>
        </div>
        
        <!-- Recent Reservations -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-purple-800">Recent Reservations</h2>
                <a href="reservations.php" class="text-purple-600 hover:text-purple-800">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Reference</th>
                            <th class="px-4 py-2 text-left">Guest Name</th>
                            <th class="px-4 py-2 text-left">Check-in</th>
                            <th class="px-4 py-2 text-left">Check-out</th>
                            <th class="px-4 py-2 text-left">Room</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Total</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentReservations)): ?>
                            <tr>
                                <td colspan="8" class="px-4 py-3 text-center text-gray-500">No reservations found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentReservations as $reservation): ?>
                                <?php
                                $statusClass = '';
                                switch ($reservation['status']) {
                                    case 'pending':
                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'confirmed':
                                        $statusClass = 'bg-green-100 text-green-800';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'bg-red-100 text-red-800';
                                        break;
                                    case 'checked_in':
                                        $statusClass = 'bg-blue-100 text-blue-800';
                                        break;
                                    case 'checked_out':
                                    case 'completed':
                                        $statusClass = 'bg-gray-100 text-gray-800';
                                        break;
                                }
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($reservation['booking_reference']); ?></td>
                                    <td class="px-4 py-3"><?php echo htmlspecialchars($reservation['customer_name']); ?></td>
                                    <td class="px-4 py-3"><?php echo date('M d, Y', strtotime($reservation['start_date'])); ?></td>
                                    <td class="px-4 py-3"><?php echo date('M d, Y', strtotime($reservation['end_date'])); ?></td>
                                    <td class="px-4 py-3">
                                        <?php 
                                        echo ucfirst($reservation['room_capacity']) . ' ' . 
                                             ucfirst($reservation['room_type']); 
                                        ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold <?php echo $statusClass; ?>">
                                            <?php echo ucfirst($reservation['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">₱<?php echo number_format($reservation['total'], 2); ?></td>
                                    <td class="px-4 py-3">
                                        <a href="edit_reservation.php?id=<?php echo $reservation['id']; ?>" class="text-blue-600 hover:text-blue-800 mr-2">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="reservations.php?action=delete&id=<?php echo $reservation['id']; ?>" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this reservation?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include 'includes/admin_footer.php'; ?>
</body>
</html>