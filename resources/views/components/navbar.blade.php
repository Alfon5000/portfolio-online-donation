<nav class="bg-gray-800 p-4">
  <div class="container mx-auto flex justify-between items-center">
    <!-- Logo -->
    <div class="text-white text-2xl font-bold">
      <a href="{{ route('index') }}">
        <img src="{{ asset('logo.png') }}" alt="{{ config('app.name') }}" class="size-12">
      </a>
    </div>

    <!-- Menu for larger screens -->
    <div class="hidden md:flex space-x-4">
      <a href="{{ route('index') }}"
        class="text-gray-300 hover:text-white {{ request()->routeIs('index') ? 'font-black text-white' : '' }}">Home</a>
      <a href="{{ route('create') }}"
        class="text-gray-300 hover:text-white {{ request()->routeIs('create') ? 'font-black text-white' : '' }}">Donate</a>
    </div>

    <!-- Hamburger button for small screens -->
    <div class="md:hidden">
      <button id="menu-btn" class="text-white focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
      </button>
    </div>
  </div>

  <!-- Dropdown Menu for small screens -->
  <div id="mobile-menu" class="hidden md:hidden">
    <a href="{{ route('index') }}"
      class="block text-gray-300 hover:text-white px-4 py-2 {{ request()->routeIs('index') ? 'font-black text-white' : '' }}">Home</a>
    <a href="{{ route('create') }}"
      class="block text-gray-300 hover:text-white px-4 py-2 {{ request()->routeIs('index') ? 'font-black text-white' : '' }}">Donate</a>
  </div>
</nav>

<script>
  const menuBtn = document.getElementById('menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');

  menuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });
</script>
