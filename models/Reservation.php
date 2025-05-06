<?php
class Reservation {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function calculateBill($customerName, $contactNumber, $startDate, $endDate, $roomType, $roomCapacity, $paymentType) {
        // Calculate number of days
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $interval = $start->diff($end);
        $days = $interval->days;
        
        // Room rates based on capacity and type
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
        
        return [
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
    }
    
    public function saveReservation($reservation) {
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
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->execute([
                ':booking_reference' => $reservation['bookingReference'],
                ':customer_name' => $reservation['customerName'],
                ':contact_number' => $reservation['contactNumber'],
                ':date_reserved' => date('Y-m-d'),
                ':start_date' => $reservation['startDate'],
                ':end_date' => $reservation['endDate'],
                ':room_type' => $reservation['roomType'],
                ':room_capacity' => $reservation['roomCapacity'],
                ':payment_type' => $reservation['paymentType'],
                ':rate_per_day' => $reservation['ratePerDay'],
                ':subtotal' => $reservation['subtotal'],
                ':additional_charge' => $reservation['additionalCharge'],
                ':discount' => $reservation['discount'],
                ':total' => $reservation['total']
            ]);
            
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }
}