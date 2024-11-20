<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    // Exibe as mensagens no dashboard
    public function index()
    {
        $messages = Contact::latest()->get();
        return view('messages', compact('messages'));
    }

    // Salva a mensagem de contato
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        Contact::create($request->all());

        return redirect()->route('contato')->with('success', 'Mensagem enviada com sucesso!');
    }
}
