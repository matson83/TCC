<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use OpenAI\Laravel\Facades\OpenAI;

class ChatController extends Controller
{
    // Método para integrar a busca e análise de discurso
    public function ajustarPontuacao($transcricao)
    {
        // Passo 1: Dividir o discurso em partes importantes para análise
        $responseDivisao = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => "
                    Aqui está o discurso transcrito:

                    {$transcricao}

                    Por favor, divida este discurso nos seguintes elementos:
                    - Título
                    - Dados relevantes
                    - Pessoas citadas
                    - Assunto
                    - Referências
                    "
                ]
            ],
            'max_tokens' => 1000,
        ]);

        $divisaoDiscurso = $responseDivisao->choices[0]->message->content;

        // Passo 2: Buscar informações na web usando SerpAPI com base no discurso
        $searchResults = $this->buscarInformacoesWeb($transcricao);

        // Passo 3: Enviar a transcrição e resultados da pesquisa à API do OpenAI para análise de legitimidade
        $responseAnalise = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => "
                    Aqui está o discurso transcrito:

                    {$transcricao}

                    Aqui estão os links relevantes encontrados na pesquisa:
                    " . implode(", ", array_map(fn($result) => $result['link'], $searchResults)) . "

                    Analise esse discurso e me passe uma comparação com as notícias dessa informação e veja se é legítima.
                    "
                ]
            ],
            'max_tokens' => 1500,
        ]);

        return [
            'divisaoDiscurso' => $divisaoDiscurso, // Retorna a divisão do discurso
            'analise' => $responseAnalise->choices[0]->message->content, // Retorna a análise de legitimidade
            'searchResults' => $searchResults // Retorna os links encontrados na web
        ];
    }

    private function buscarInformacoesWeb($transcricao)
    {
        $client = new Client();
        $query = urlencode($transcricao); // URL-encode a transcrição para a busca

        // Configuração correta com a chave de API da SerpAPI
        $response = $client->get('https://serpapi.com/search.json', [
            'query' => [
                'q' => $query,
                'api_key' => env('SERP_API_KEY'), // Use a chave do .env
            ],
        ]);

        // Processar os resultados
        $searchResults = json_decode($response->getBody()->getContents(), true);

        // Inicializa um array para armazenar os links formatados
        $resultadosFormatados = [];
        if (isset($searchResults['organic_results'])) {
            foreach ($searchResults['organic_results'] as $result) {
                $resultadosFormatados[] = [
                    'title' => $result['title'],
                    'link' => $result['link']
                ];
            }
        }

        return $resultadosFormatados; // Retorna um array de resultados
    }
}
