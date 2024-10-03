<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transcricao extends Model
{
    use HasFactory;

    // Definir o nome da tabela explicitamente
    protected $table = 'transcricoes';

    // Permitir atribuição em massa dos seguintes campos
    protected $fillable = ['caminho_audio', 'transcricao_ajustada'];
}
