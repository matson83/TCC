<x-app-layout>
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <style>


                        h2 {
                            color: #4CAF50;
                            margin-bottom: 10px;
                        }

                        ul {
                            list-style-type: none;
                            padding: 0;
                        }

                        li {
                            background-color: #04171f;
                            border: 1px solid #ddd;
                            border-radius: 5px;
                            padding: 15px;
                            margin: 10px 0;
                            transition: box-shadow 0.3s;
                        }

                        li:hover {
                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                        }

                        strong {
                            color: #555;
                        }

                        hr {
                            border: 0;
                            height: 1px;
                            background-color: #ddd;
                            margin: 10px 0;
                        }

                        p {
                            font-size: 1.2em;
                            text-align: center;
                        }
                    </style>



                    @if(isset($transcricoes) && $transcricoes->isNotEmpty())
                    <h2>Transcrições Anteriores e Pesquisas na rede:</h2>
                    <ul>
                        @foreach($transcricoes as $transcricao)
                            <li>
                                <strong>Transcrição Ajustada:</strong> {{ $transcricao->transcricao_ajustada }}<br>
                                <strong>Criado em:</strong> {{ $transcricao->created_at }}
                            </li>
                            <hr>
                        @endforeach
                    </ul>
                @else
                    <p>Nenhuma transcrição disponível.</p>
                @endif



            </div>
        </div>
    </div>
</x-app-layout>
