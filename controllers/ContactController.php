<?php
require_once 'controllers/Controller.php';

class ContactController extends Controller {
    public function index() {
        // Process form submission
        $messageSent = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
            // In a real application, you would process the form data here
            // For example, send an email or store in database
            $messageSent = true;
        }
        
        $this->view('contact/contact', [
            'messageSent' => $messageSent
        ]);
    }
}