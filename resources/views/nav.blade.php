<header class="bg-red-500 shadow-lg">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center py-4">
        <!-- Logo -->
        <a href="/" class="text-white text-2xl font-bold">Domiradios</a>

        <!-- Toggle Button for Mobile -->
        <div class="sm:hidden">
            <button id="menu-toggle" class="text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>

        <!-- Menu Items -->
        <ul id="menu" class="hidden sm:flex space-x-8">
            <li><a href="/" class="text-white text-lg hover:text-gray-300 transition duration-300">Home</a></li>
            <li><a href="/about" class="text-white text-lg hover:text-gray-300 transition duration-300">About</a></li>
            <li><a href="/contact" class="text-white text-lg hover:text-gray-300 transition duration-300">Contact</a></li>
        </ul>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden sm:hidden px-4">
        <ul class="flex flex-col space-y-4">
            <li><a href="/" class="text-white text-lg hover:text-gray-300 transition duration-300">Home</a></li>
            <li><a href="/about" class="text-white text-lg hover:text-gray-300 transition duration-300">About</a></li>
            <li><a href="/contact" class="text-white text-lg hover:text-gray-300 transition duration-300">Contact</a></li>
        </ul>
    </div>
</header>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>

