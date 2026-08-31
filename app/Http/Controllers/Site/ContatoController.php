<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Mail\ContatoLead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class ContatoController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:30'],
            'mensagem' => ['required', 'string', 'max:2000'],
        ]);

        $key = 'contato:' . mb_strtolower($validated['email']);
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json([
                'message' => 'Muitas tentativas. Tente novamente em alguns minutos.',
            ], 429);
        }

        RateLimiter::hit($key, 3600);

        Mail::send(new ContatoLead(
            nome: $validated['nome'],
            email: $validated['email'],
            telefone: $validated['telefone'] ?? '',
            mensagem: $validated['mensagem'],
        ));

        return response()->json([
            'message' => 'Recebemos o seu contato e retornaremos em breve.',
        ]);
    }
}
