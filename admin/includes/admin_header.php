<header class="bg-purple-800 text-white shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <h1 class="text-xl font-bold">Purple Hotel Admin</h1>
            </div>
            
            <div class="hidden md:flex items-center space-x-6">
                <a href="dashboard.php" class="hover:text-purple-200">Dashboard</a>
                <a href="reservations.php" class="hover:text-purple-200">Reservations</a>
                <a href="../index.php" class="hover:text-purple-200" target="_blank">View Website</a>
            </div>
            
            <button id="mobile-menu-button" class="md:hidden flex items-center">
                <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                    <path d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"/>
                </svg>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden pb-4">
            <a href="dashboard.php" class="block py-2 hover:text-purple-200">Dashboard</a>
            <a href="reservations.php" class="block py-2 hover:text-purple-200">Reservations</a>
            <a href="../index.php" class="block py-2 hover:text-purple-200">View Website</a>
        </div>
    </div>
</header>

<script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    });
</script>