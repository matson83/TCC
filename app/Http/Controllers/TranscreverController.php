<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionAudio;

class TranscreverController extends Controller
{
    //
    // Método para transcrever o áudio usando a API do Google Cloud
    public function transcreverAudio($filePath)
    {
        $speechClient = new SpeechClient([
            'credentials' => storage_path('google-cloud-key.json')
        ]);

        $config = new RecognitionConfig([
            'encoding' => RecognitionConfig\AudioEncoding::MP3,
            'sample_rate_hertz' => 20000,
            'language_code' => 'pt-BR',
        ]);

        $audio = (new RecognitionAudio())->setContent(file_get_contents($filePath));

        $response = $speechClient->recognize($config, $audio);
        $speechClient->close();

        $transcricao = '';
        foreach ($response->getResults() as $result) {
            $transcricao .= $result->getAlternatives()[0]->getTranscript() . ' ';
        }

        return trim($transcricao);
    }
}
