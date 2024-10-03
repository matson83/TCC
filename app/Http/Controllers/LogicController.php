<?php

namespace App\Http\Controllers;

use App\Models\Transcricao;
use Illuminate\Http\Request;

class LogicController extends Controller
{
    public function index()
    {
        // Busca todas as transcrições
        $transcricoes = Transcricao::all();

        // Retorna a view com as transcrições
        return view('transcricoes', compact('transcricoes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'audio' => 'required|mimes:wav,mp3|max:10240',
        ]);

        // Salvando o arquivo no diretório 'audios'
        $filePath = $request->file('audio')->store('audios');
        $fullFilePath = storage_path('app/' . $filePath);

        // Verifica a duração do áudio
        $verificarDuracao = new VerificarduracaoController;
        $duracao = $verificarDuracao->verificarDuracao($fullFilePath);

        if ($duracao > 180) {  // 120 segundos = 2 minutos
            // Busca todas as transcrições antigas
            $transcricoes = Transcricao::all();
            return back()->withErrors(['audio' => 'O áudio enviado é muito longo. O limite é de 2 minutos.'])->with(compact('transcricoes'));
        }

        // Transcreve o áudio
        $transcreverAudio = new TranscreverController;
        $transcricao = $transcreverAudio->transcreverAudio($fullFilePath);

        // Ajustar a pontuação e buscar links
        $ajustarPontuacao = new ChatController;
        $resultado = $ajustarPontuacao->ajustarPontuacao($transcricao);

        $transcricaoAjustada = $resultado['analise']; // Usar chave associativa 'analise'
        $links = $resultado['searchResults']; // Usar chave associativa 'searchResults'

        // Salva a transcrição no banco de dados
        Transcricao::create([
            'caminho_audio' => $filePath,
            'transcricao_ajustada' => $transcricaoAjustada,
        ]);

        // Busca todas as transcrições antigas
        $transcricoes = Transcricao::all();

        // Retorna a view com a nova transcrição e as transcrições antigas
        return view('welcome', compact('transcricaoAjustada', 'links', 'transcricoes'));
    }
}
