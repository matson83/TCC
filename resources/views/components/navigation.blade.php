
<nav class="bg-gray-800 border-b dark:bg-gray-900 mb-4">
    <div class="w-full">
        <div class="flex justify-between h-16">
            <div class="flex items-center justify-end w-full ">
                <div class="space-x-8 flex px-4">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Painel Administrador') }}
                    </x-nav-link>
                    <x-nav-link :href="route('contato')" :active="request()->routeIs('dashboard')">
                        {{ __('Contato') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Informações do Trabalho') }}
                    </x-nav-link>
                </div>
            </div>
        </div>
    </div>
</nav>
