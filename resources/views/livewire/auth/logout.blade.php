<button 
    wire:click="logout"
    class="w-full text-left px-4 py-3 font-medium transition-colors font-robotoCondensed text-white bg-background-color-3 hover:bg-red-700"
>
    <i class="fas fa-sign-out-alt mr-3"></i>
    {{ trans('my-account.logout') }}
</button>