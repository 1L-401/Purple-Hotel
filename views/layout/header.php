<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purple Hotel - <?php echo isset($pageTitle) ? $pageTitle : 'Welcome'; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        .customer-heading {
            background-color: #6b21a8;
            color: white;
            padding: 15px;
            margin: -24px -24px 24px -24px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .content-section {
            opacity: 1;
            display: block;
        }
        .profile-content {
            padding: 20px;
        }
    </style>
</head>
<body class="bg-gray-100">
<nav class="bg-purple-800 text-white shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div>
                <a href="index.php?url=home" class="text-xl font-bold">PURPLE HOTEL</a>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-6">
                <a href="index.php?url=home" class="hover:text-purple-200 transition">Home</a>
                <a href="index.php?url=company" class="hover:text-purple-200 transition">About Us</a>
                <a href="index.php?url=reservation" class="hover:text-purple-200 transition">Reservation</a>
                <a href="index.php?url=contact" class="hover:text-purple-200 transition">Contact</a>
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
            <a href="index.php?url=home" class="block py-2 hover:text-purple-200 transition">Home</a>
            <a href="index.php?url=company" class="block py-2 hover:text-purple-200 transition">About Us</a>
            <a href="index.php?url=reservation" class="block py-2 hover:text-purple-200 transition">Reservation</a>
            <a href="index.php?url=contact" class="block py-2 hover:text-purple-200 transition">Contact</a>
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
</body>
</html>