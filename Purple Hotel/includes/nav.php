<nav class="bg-purple-800 text-white shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div>
                <a href="index.php" class="text-xl font-bold">PURPLE HOTEL</a>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-6">
                <a href="index.php" class="hover:text-purple-200 transition">Home</a>
                <a href="company_profile.php" class="hover:text-purple-200 transition">About Us</a>
                <a href="reservation.php" class="hover:text-purple-200 transition">Reservation</a>
                <a href="contact.php" class="hover:text-purple-200 transition">Contact</a>
            </div>
            
            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-white focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden pb-4">
            <a href="index.php" class="block py-2 hover:text-purple-200 transition">Home</a>
            <a href="company_profile.php" class="block py-2 hover:text-purple-200 transition">About Us</a>
            <a href="reservation.php" class="block py-2 hover:text-purple-200 transition">Reservation</a>
            <a href="contact.php" class="block py-2 hover:text-purple-200 transition">Contact</a>
        </div>
    </div>
</nav>

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