<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #4CAF50;
            margin-bottom: 10px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #fff;
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
</head>
<body>

    @if(isset($transcricoes) && $transcricoes->isNotEmpty())
    <h2>Transcrições Anteriores:</h2>
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

</body>
</html>
