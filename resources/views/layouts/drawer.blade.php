<aside id="drawer" class="min-w-max w-auto h-screen bg-white shadow-md fixed left-0 top-0 z-40 flex flex-col transition-transform duration-200 ease-in-out px-6 pt-0" style="transform: translateX(0);">
    <div class="h-16 flex items-center justify-between border-b font-bold text-lg px-4">
        <span>Menu</span>
        <button id="drawer-toggle" class="text-gray-500 hover:text-gray-800 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <nav class="flex-1 p-4">
        <ul class="space-y-4">
            <li>
                <a href="{{ route('mental') }}" class="flex items-center text-gray-700 hover:text-blue-600">
                    Calcul mental
                </a>
            </li>
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center text-gray-700 hover:text-blue-600">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('profile.page') }}" class="flex items-center text-gray-700 hover:text-blue-600">
                    Profil
                </a>
            </li>
            <li>
                <a href="{{ route('settings') }}" class="flex items-center text-gray-700 hover:text-blue-600">
                    Paramètres
                </a>
            </li>
        </ul>
    </nav>
</aside>
<button id="drawer-open" class="fixed top-4 left-4 z-50 bg-white p-2 rounded shadow-md md:hidden" style="display: none;">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>
<script>
    const drawer = document.getElementById('drawer');
    const toggleBtn = document.getElementById('drawer-toggle');
    const openBtn = document.getElementById('drawer-open');
    function closeDrawer() {
        drawer.style.transform = 'translateX(-100%)';
        openBtn.style.display = 'block';
    }
    function openDrawer() {
        drawer.style.transform = 'translateX(0)';
        openBtn.style.display = 'none';
    }
    if (toggleBtn && openBtn && drawer) {
        toggleBtn.addEventListener('click', closeDrawer);
        openBtn.addEventListener('click', openDrawer);
    }
    // Responsive: cacher drawer sur mobile au départ
    if (window.innerWidth < 768) {
        closeDrawer();
    }
</script>
