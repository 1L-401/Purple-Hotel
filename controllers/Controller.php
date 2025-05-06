<?php
class Controller {
    protected $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    protected function view($view, $data = []) {
        // Extract the data array to variables
        extract($data);
        
        // Include header
        require_once 'views/layout/header.php';
        
        // Include the requested view
        require_once 'views/' . $view . '.php';
        
        // Include footer
        require_once 'views/layout/footer.php';
    }
    
    protected function redirect($url) {
        header('Location: index.php?url=' . $url);
        exit;
    }
}