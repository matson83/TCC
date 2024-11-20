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
                    Aqui está a transcrição de um discurso:

                    {$transcricao}

                    Título ou Resumo:

                        Forneça um título claro ou um resumo breve e objetivo do discurso, destacando sua ideia principal.
                        Dados Relevantes:

                        Liste todos os fatos, datas, números ou estatísticas mencionados no discurso.
                        Certifique-se de incluir os detalhes de maneira precisa, sem distorções ou omissões.
                        Pessoas e Entidades Citadas:

                        Identifique todas as pessoas, organizações ou entidades mencionadas no discurso.
                        Inclua cargos, funções ou contextos relevantes para entender melhor as citações.
                        Temas Centrais:

                        Identifique o principal tema ou objetivo do discurso. O que está sendo comunicado? Qual é a mensagem central?
                        Referências ou Citações Diretas:

                        Inclua todas as referências, citações ou fontes mencionadas no discurso.
                        Certifique-se de que as fontes estão corretamente atribuídas e que as citações são precisas.
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

                  Comparação com Fontes Externas:

                        Compare o conteúdo do discurso com as informações fornecidas nos links ou fontes externas indicadas.
                        Verifique a consistência dos dados, confirmando se as informações do discurso estão alinhadas com as fontes externas.
                        Identificação de Discrepâncias ou Erros:

                        Identifique e descreva qualquer discrepância, erro factual ou informações que não estejam corroboradas pelas fontes externas de alta credibilidade.
                        Verifique se existem informações desatualizadas, distorcidas ou ausentes nas fontes.
                        Avaliação da Legitimidade:

                        Com base na comparação das informações, atribua uma avaliação de legitimidade:
                        Alta: As informações são precisas, bem fundamentadas e corroboradas por fontes confiáveis.
                        Média: Existem algumas inconsistências ou fontes questionáveis, mas o discurso ainda possui uma base razoável de legitimidade.
                        Baixa: O discurso contém múltiplas discrepâncias, dados imprecisos ou informações não verificadas que comprometem sua confiabilidade.
                        Justificação Detalhada:

                        Explique de maneira detalhada os passos da sua análise, descrevendo claramente as fontes usadas, as discrepâncias encontradas e as razões para a avaliação de legitimidade atribuída.
                        Se necessário, forneça exemplos específicos de erros ou inconsistências identificadas e como elas impactam a credibilidade do discurso.
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

        $resultadosFormatados = [];
        if (isset($searchResults['organic_results'])) {
            foreach ($searchResults['organic_results'] as $result) {
                $resultadosFormatados[] = [
                    'title' => $result['title'],
                    'link' => $result['link'],
                    'snippet' => $result['snippet'] ?? '', // Inclui um resumo quando disponível
                    'source' => $result['displayed_link'] ?? '', // Fonte do link
                ];
            }
        }

        // Refine os links para priorizar os mais importantes
        $resultadosFormatados = $this->refinarLinks($resultadosFormatados, $transcricao);

        return $resultadosFormatados; // Retorna um array de resultados refinados
    }

    private function refinarLinks(array $resultados, string $transcricao)
    {
        $palavrasChave = explode(' ', $transcricao); // Extrai palavras-chave da transcrição


        $fontesConfiaveis = [
            'bbc.com',
            'nytimes.com',
            'reuters.com',
            'forbes.com',
            'cnn.com', // fontes internacionais
            'senado.leg.br',
            'g1.globo.com',
            'uol.com.br',
            'folha.uol.com.br',
            'veja.abril.com.br', // fontes brasileiras
            'estadao.com.br',
            'correio24horas.com.br',
            'oglobo.globo.com',
            'jornalextra.globo.com',
            'exame.com',
            'terra.com.br',
            'r7.com',
            'brasil247.com',
        ];

        // Adiciona pesos para fontes confiáveis
        foreach ($resultados as &$resultado) {
            $peso = 0;

            // Aumenta o peso se a fonte for confiável
            foreach ($fontesConfiaveis as $fonte) {
                if (strpos($resultado['source'], $fonte) !== false) {
                    $peso += 5;
                    break;
                }
            }

            // Aumenta o peso se o título ou snippet contiver palavras-chave relevantes
            foreach ($palavrasChave as $palavra) {
                if (stripos($resultado['title'], $palavra) !== false || stripos($resultado['snippet'], $palavra) !== false) {
                    $peso += 2;
                }
            }

            $resultado['peso'] = $peso; // Armazena o peso no array de resultados
        }

        // Ordena os resultados pelo peso em ordem decrescente
        usort($resultados, fn($a, $b) => $b['peso'] <=> $a['peso']);

        // Retorna os 5 primeiros links mais relevantes
        return array_slice($resultados, 0, 5);
    }
}
