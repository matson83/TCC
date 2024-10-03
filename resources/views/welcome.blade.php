@include('includes.header')

<!-- Formulário de upload de áudio -->
<form action="{{ route('transcricao.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="audio">Selecione um arquivo de áudio (MP3):</label>
    <input type="file" name="audio" id="audio" accept=".mp3" required>
    <button type="submit">Transcrever Áudio</button>
</form>

<!-- Exibição dos erros, se houver -->
@if ($errors->any())
    <div class="error-messages">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Exibição da transcrição ajustada, se disponível -->
@isset($transcricaoAjustada)
    <h2>Informações sobre o discurso e sua verificação:</h2>
    <p id="transcricao">{{$transcricaoAjustada}}</p>

    <h3>Links encontrados:</h3>
    <ul>
        @if(is_array($links) && count($links) > 0)
            @foreach ($links as $link)
                <li><a href="{{ $link['link'] }}" target="_blank">{{ $link['title'] }}</a></li>
            @endforeach
        @else
            <li>Nenhum link encontrado.</li>
        @endif
    </ul>
@endisset


<div class="button-container">
    <a href="{{ route('transcricao.index') }}">
        <button type="button">Ver Lista de Transcrições</button>
    </a>
</div>

@include('includes.footer')
