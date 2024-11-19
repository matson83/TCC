@include('includes.header')

<body class="bg-gray-800">

    <!-- Navigation -->
    <nav class="bg-gray-800 border-b dark:bg-gray-900 mb-4">
        <div class="w-full">
            <div class="flex justify-between h-16">
                <div class="flex items-center justify-end w-full ">
                    <div class="space-x-8 flex px-4">
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Painel Administrador') }}
                        </x-nav-link>
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
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


    <!-- Formulário de upload-->
    <form action="{{ route('transcricao.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="input-group p-4">
            <input type="file" class="form-control" name="audio" id="audio" accept=".mp3" aria-label="Upload" required>
            <button class="btn btn-outline-secondary" type="submit" id="button">Transcrever Áudio</button>
        </div>

    </form>

    <!-- Exibição dos erros-->
    @if ($errors->any())
        <div class="error-messages">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Exibição da transcrição ajustada-->
    @isset($transcricaoAjustada)
    <div class=" p-4">
        <h2 class="text-white" >Informações sobre o discurso e sua verificação:</h2>
        <p id="transcricao" class="text-white">{{$transcricaoAjustada}}</p>

        <h3 class="text-white" >Links encontrados:</h3>
        <ul>
            @if(is_array($links) && count($links) > 0)
                @foreach ($links as $link)
                    <li><a href="{{ $link['link'] }}" target="_blank">{{ $link['title'] }}</a></li>
                @endforeach
            @else
                <li class="text-white" >Nenhum link encontrado.</li>
            @endif
        </ul>
    </div>

    @endisset

</body>


@include('includes.footer')
