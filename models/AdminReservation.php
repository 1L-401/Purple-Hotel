<?php
class AdminReservation {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAllReservations($page = 1, $per_page = 10, $filters = [], $search = '') {
        $where_conditions = [];
        $params = [];
        
        // Handle filters
        if (!empty($filters['status'])) {
            $where_conditions[] = "status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['date'])) {
            $where_conditions[] = "(start_date <= ? AND end_date >= ?)";
            $params[] = $filters['date'];
            $params[] = $filters['date'];
        }
        
        if (!empty($search)) {
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
        
        // Calculate offset for pagination
        $offset = ($page - 1) * $per_page;
        
        // Get total count for pagination
        try {
            $count_sql = "SELECT COUNT(*) FROM reservations $where_clause";
            $count_stmt = $this->db->prepare($count_sql);
            $count_stmt->execute($params);
            $total_records = $count_stmt->fetchColumn();
            $total_pages = ceil($total_records / $per_page);
        } catch(PDOException $e) {
            throw new Exception("Failed to count reservations: " . $e->getMessage());
        }
        
        // Get reservations
        try {
            $sql = "SELECT * FROM reservations $where_clause ORDER BY id DESC LIMIT $offset, $per_page";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $reservations = $stmt->fetchAll();
            
            return [
                'reservations' => $reservations,
                'total_records' => $total_records,
                'total_pages' => $total_pages,
                'current_page' => $page
            ];
        } catch(PDOException $e) {
            throw new Exception("Failed to retrieve reservations: " . $e->getMessage());
        }
    }
    
    public function updateReservationStatus($id, $status) {
        try {
            $stmt = $this->db->prepare("UPDATE reservations SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            return true;
        } catch(PDOException $e) {
            throw new Exception("Failed to update reservation status: " . $e->getMessage());
        }
    }
    
    public function deleteReservation($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM reservations WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        } catch(PDOException $e) {
            throw new Exception("Failed to delete reservation: " . $e->getMessage());
        }
    }
}