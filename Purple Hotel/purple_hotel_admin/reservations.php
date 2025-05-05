<?php
session_start();
date_default_timezone_set('Asia/Manila');



// Include database connection
require_once '../includes/db_connect.php';

// Handle status changes or deletion if needed
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    
    try {
        if ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Reservation deleted successfully.";
        } else if (in_array($action, ['confirm', 'cancel', 'checkin', 'checkout'])) {
            $statusMap = [
                'confirm' => 'confirmed',
                'cancel' => 'cancelled',
                'checkin' => 'checked_in',
                'checkout' => 'checked_out'
            ];
            
            $stmt = $pdo->prepare("UPDATE reservations SET status = ? WHERE id = ?");
            $stmt->execute([$statusMap[$action], $id]);
            $message = "Reservation status updated successfully.";
        }
    } catch(PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

// Prepare filter conditions
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$where_conditions = [];
$params = [];

if ($status_filter) {
    $where_conditions[] = "status = ?";
    $params[] = $status_filter;
}

if ($date_filter) {
    $where_conditions[] = "(start_date <= ? AND end_date >= ?)";
    $params[] = $date_filter;
    $params[] = $date_filter;
}

if ($search) {
    $where_conditions[] = "(customer_name LIKE ? OR booking_reference LIKE ? OR contact_number LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

$where_clause = '';
if (!empty($where_conditions)) {
    $where_clause = "WHERE " . implode(" AND ", $where_conditions);
}

// Define pagination variables
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10; // Number of records per page
$offset = ($page - 1) * $per_page;

// Get total count for pagination
try {
    $count_sql = "SELECT COUNT(*) FROM reservations $where_clause";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute($params);
    $total_records = $count_stmt->fetchColumn();
    $total_pages = ceil($total_records / $per_page);
} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
    $total_records = 0;
    $total_pages = 0;
}

// Get reservations from database
try {
    $sql = "SELECT * FROM reservations $where_clause ORDER BY id DESC LIMIT $offset, $per_page";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $reservations = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Database error: " . $e->getMessage();
    $reservations = [];
}

?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reservations - Purple Hotel Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Admin Header -->
    <?php include 'includes/admin_header.php'; ?>
    
    <div class="container mx-auto px-4 py-8 flex-grow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-purple-800">
                <?php 
                if (!empty($status_filter)) {
                    echo ucfirst($status_filter) . " Reservations";
                } else {
                    echo "All Reservations";
                }
                ?>
            </h1>
            
            <a href="export_reservations.php" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
                <i class="fas fa-file-export mr-2"></i> Export to CSV
            </a>
        </div>
        
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $_SESSION['success_message']; ?></span>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $_SESSION['error_message']; ?></span>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>
        
        <!-- Filters and Search -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                <div class="flex flex-col md:flex-row md:space-x-4 space-y-4 md:space-y-0">
                    <a href="reservations.php" class="inline-block px-4 py-2 rounded <?php echo empty($status_filter) ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                        All
                    </a>
                    <a href="?status=pending" class="inline-block px-4 py-2 rounded <?php echo $status_filter === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                        Pending
                    </a>
                    <a href="?status=confirmed" class="inline-block px-4 py-2 rounded <?php echo $status_filter === 'confirmed' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                        Confirmed
                    </a>
                    <a href="?status=checked_in" class="inline-block px-4 py-2 rounded <?php echo $status_filter === 'checked_in' ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                        Checked In
                    </a>
                    <a href="?status=completed" class="inline-block px-4 py-2 rounded <?php echo $status_filter === 'completed' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                        Completed
                    </a>
                    <a href="?status=cancelled" class="inline-block px-4 py-2 rounded <?php echo $status_filter === 'cancelled' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                        Cancelled
                    </a>
                </div>
                
                <form action="" method="GET" class="flex">
                    <?php if (!empty($status_filter)): ?>
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($status_filter); ?>">
                    <?php endif; ?>
                    <input type="text" name="search" placeholder="Search reservations..." value="<?php echo htmlspecialchars($search); ?>" 
                        class="rounded-l border-r-0 focus:ring-purple-500 focus:border-purple-500">
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-r hover:bg-purple-700">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Reservations Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Reference</th>
                            <th class="px-4 py-2 text-left">Guest Name</th>
                            <th class="px-4 py-2 text-left">Contact</th>
                            <th class="px-4 py-2 text-left">Check-in</th>
                            <th class="px-4 py-2 text-left">Check-out</th>
                            <th class="px-4 py-2 text-left">Room</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Total</th>
                            <th class="px-4 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute($params);
                            $reservations = $stmt->fetchAll();
                            
                            if (count($reservations) > 0) {
                                foreach ($reservations as $row) {
                                    $statusClass = '';
                                    switch ($row['status']) {
                                        case 'pending':
                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                            break;
                                        case 'confirmed':
                                            $statusClass = 'bg-green-100 text-green-800';
                                            break;
                                        case 'checked_in':
                                            $statusClass = 'bg-indigo-100 text-indigo-800';
                                            break;
                                        case 'cancelled':
                                            $statusClass = 'bg-red-100 text-red-800';
                                            break;
                                        case 'completed':
                                            $statusClass = 'bg-blue-100 text-blue-800';
                                            break;
                                    }
                                    ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3"><?php echo $row['booking_reference']; ?></td>
                                        <td class="px-4 py-3"><?php echo $row['customer_name']; ?></td>
                                        <td class="px-4 py-3"><?php echo $row['contact_number']; ?></td>
                                        <td class="px-4 py-3"><?php echo date('M d, Y', strtotime($row['start_date'])); ?></td>
                                        <td class="px-4 py-3"><?php echo date('M d, Y', strtotime($row['end_date'])); ?></td>
                                        <td class="px-4 py-3 capitalize"><?php echo $row['room_type']; ?></td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $statusClass; ?>">
                                                <?php echo ucfirst($row['status']); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">₱<?php echo number_format($row['total'], 2); ?></td>
                                        <td class="px-4 py-3">
                                            <div class="flex space-x-2">
                                                <a href="view_reservation.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="edit_reservation.php?id=<?php echo $row['id']; ?>" class="text-purple-600 hover:text-purple-800" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="reservations.php?action=delete&id=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this reservation? This action cannot be undone.')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <button type="button" class="text-gray-600 hover:text-gray-800 status-dropdown-btn" data-id="<?php echo $row['id']; ?>" title="Change Status">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </button>
                                                <!-- Status Dropdown (hidden by default) -->
                                                <div id="status-dropdown-<?php echo $row['id']; ?>" class="status-dropdown hidden absolute mt-8 bg-white border rounded shadow-md z-10">
                                                    <form method="POST" action="">
                                                        <input type="hidden" name="action" value="update_status">
                                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                        <button type="submit" name="status" value="pending" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">Pending</button>
                                                        <button type="submit" name="status" value="confirmed" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">Confirmed</button>
                                                        <button type="submit" name="status" value="checked_in" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">Checked In</button>
                                                        <button type="submit" name="status" value="completed" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">Completed</button>
                                                        <button type="submit" name="status" value="cancelled" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">Cancelled</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="9" class="px-4 py-6 text-center text-gray-500">No reservations found</td></tr>';
                            }
                        } catch(PDOException $e) {
                            echo '<tr><td colspan="9" class="px-4 py-6 text-center text-red-500">Error: ' . $e->getMessage() . '</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="px-4 py-3 flex items-center justify-between border-t">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium"><?php echo $offset + 1; ?></span> to 
                            <span class="font-medium"><?php echo min($offset + $per_page, $total_records); ?></span> of 
                            <span class="font-medium"><?php echo $total_records; ?></span> results
                        </p>
                    </div>
                    <div>
                        <nav class="flex justify-center">
                            <ul class="flex pl-0 list-none rounded my-2">
                                <?php
                                $query_params = [];
                                if (!empty($status_filter)) $query_params[] = "status=$status_filter";
                                if (!empty($search)) $query_params[] = "search=" . urlencode($search);
                                $query_string = !empty($query_params) ? "&" . implode("&", $query_params) : "";
                                
                                // Previous button
                                if ($page > 1): ?>
                                    <li>
                                        <a href="?page=<?php echo $page - 1 . $query_string; ?>" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-l hover:bg-gray-300">
                                            Previous
                                        </a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php
                                // Page numbers
                                $start_page = max(1, $page - 2);
                                $end_page = min($total_pages, $page + 2);
                                
                                for ($i = $start_page; $i <= $end_page; $i++): ?>
                                    <li>
                                        <a href="?page=<?php echo $i . $query_string; ?>" 
                                            class="px-3 py-1 <?php echo $i === $page ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                
                                <!-- Next button -->
                                <?php if ($page < $total_pages): ?>
                                    <li>
                                        <a href="?page=<?php echo $page + 1 . $query_string; ?>" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-r hover:bg-gray-300">
                                            Next
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Admin Footer -->
    <?php include 'includes/admin_footer.php'; ?>
    
    <script>
        // Toggle status dropdowns
        document.addEventListener('DOMContentLoaded', function() {
            const statusButtons = document.querySelectorAll('.status-dropdown-btn');
            
            statusButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const dropdown = document.getElementById(`status-dropdown-${id}`);
                    
                    // Close all other dropdowns
                    document.querySelectorAll('.status-dropdown').forEach(el => {
                        if (el.id !== `status-dropdown-${id}`) {
                            el.classList.add('hidden');
                        }
                    });
                    
                    // Toggle current dropdown
                    dropdown.classList.toggle('hidden');
                });
            });
            
            // Close dropdowns when clicking elsewhere
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.status-dropdown-btn') && !event.target.closest('.status-dropdown')) {
                    document.querySelectorAll('.status-dropdown').forEach(el => {
                        el.classList.add('hidden');
                    });
                }
            });
        });
    </script>
</body>
</html>