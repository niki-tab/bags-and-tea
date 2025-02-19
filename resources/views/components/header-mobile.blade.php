<div class="md:hidden bg-button-color-1 text-white h-28 fixed top-0 w-full">
    <div class="flex justify-between items-center"> 
        <!-- Logo (on the left) -->
        <div class="text-left mt-2 p-4">
            <a href="/{{app()->getLocale()}}" class="">
                <img src="{{ asset('images/logo/logo_new_02.png') }}" alt="logo" width="200" height="78">
            </a>
        </div>

        <!-- Hamburger Menu (on the right) -->
        <div class="text-right mr-4 p-4">
            <!-- Toggle Menu on Click -->
            <button id="hamburgerMenu" class="focus:outline-none">
                <img src="{{ asset('images/icons/hamburguer_menu_2.png') }}" alt="hamburger menu" width="45" height="78">
            </button>
        </div>
    </div>

</div>
<script>
    // JavaScript to toggle the menu
    document.getElementById('hamburgerMenu').addEventListener('click', function() {
        var menu = document.getElementById('mobileMenu');
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
        } else {
            menu.classList.add('hidden');
        }
    });
</script>