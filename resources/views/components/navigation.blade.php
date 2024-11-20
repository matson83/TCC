<nav class="mb-4 bg-gray-800 border-b dark:bg-gray-900">
    <div class="w-full">
        <div class="flex justify-between h-16">
            <div class="flex items-center px-4 shrink-0">
                <a href="{{ route('transcricao.form') }}">
                    <x-application-logo class="block w-auto text-gray-800 fill-current h-9 dark:text-gray-200" />
                </a>
            </div>
            <div class="flex items-center justify-end w-full">
                <div class="flex px-4 space-x-8">
                    <x-nav-link :href="route('login')" :active="request()->routeIs('dashboard')">
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
