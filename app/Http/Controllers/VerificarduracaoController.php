<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use getID3;

class VerificarduracaoController extends Controller
{
    // Método para verificar a duração do áudio (em segundos)
    public function verificarDuracao($filePath)
    {
        $getID3 = new getID3;
        $fileInfo = $getID3->analyze($filePath);

        return $fileInfo['playtime_seconds'] ?? 0;
    }
}
