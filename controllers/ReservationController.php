<?php
require_once 'controllers/Controller.php';
require_once 'models/Reservation.php';

class ReservationController extends Controller {
    private $reservationModel;
    
    public function __construct() {
        parent::__construct();
        $this->reservationModel = new Reservation($this->db);
    }
    
    public function index() {
        $this->view('reservation/form');
    }
    
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reservation'])) {
            // Collect form data
            $customerName = $_POST['customerName'] ?? '';
            $contactNumber = $_POST['contactNumber'] ?? '';
            $startDate = $_POST['startDate'] ?? '';
            $endDate = $_POST['endDate'] ?? '';
            $roomType = $_POST['roomType'] ?? '';
            $roomCapacity = $_POST['roomCapacity'] ?? '';
            $paymentType = $_POST['paymentType'] ?? '';
            
            // Calculate the bill
            $bill = $this->reservationModel->calculateBill(
                $customerName, $contactNumber, $startDate, $endDate,
                $roomType, $roomCapacity, $paymentType
            );
            
            // Store in session
            $_SESSION['bill'] = $bill;
            
            // Save to database
            $this->reservationModel->saveReservation($bill);
            
            // Redirect to billing page
            $this->redirect('reservation/billing');
        } else {
            $this->redirect('reservation');
        }
    }
    
    public function billing() {
        if (empty($_SESSION['bill'])) {
            $this->redirect('reservation');
        }
        
        $this->view('reservation/billing', [
            'bill' => $_SESSION['bill']
        ]);
    }
    
    public function confirmation() {
        if (empty($_SESSION['bill'])) {
            $this->redirect('reservation');
        }
        
        $this->view('reservation/confirmation', [
            'bill' => $_SESSION['bill']
        ]);
    }
}