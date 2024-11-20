@include('includes.header')

<body class="bg-gray-800">

    <x-navigation/>


    <!-- Formulário de upload-->
    <form action="{{ route('transcricao.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="input-group p-4">
            <input type="file" class="form-control" name="audio" id="audio" accept=".mp3" aria-label="Upload" required>
            <button class="btn btn-outline-secondary" type="submit" id="button">Analisar Áudio</button>
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
