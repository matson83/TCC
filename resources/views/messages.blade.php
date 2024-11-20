<x-app-layout>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Mensagens Recebidas</h1>

        @if ($messages->isEmpty())
            <div class="alert alert-info text-center">
                Nenhuma mensagem encontrada.
            </div>
        @else
        <table class="table custom-table">
            <thead>
                <tr>

                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Telefone</th>
                    <th scope="col">Mensagem</th>
                    <th scope="col">Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($messages as $message)
                <tr>
                    
                    <td>{{ $message->id }}</td>
                    <td><a href="#">{{ $message->name }}</a></td>
                    <td>{{ $message->email }}</td>
                    <td>{{ $message->phone }}</td>
                    <td>{{ $message->message }}</td>
                    <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr class="spacer"><td colspan="100"></td></tr>
                @endforeach
            </tbody>
        </table>


        @endif
    </div>
</x-app-layout>
