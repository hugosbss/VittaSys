<?php

namespace App\Http\Controllers;

use App\Models\LoteProduto;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 10);
        $perPage = max(1, min($perPage, 100));

        $query = Produto::query()->with('loteAtual');

        if ($request->filled('search')) {
            $search = (string) $request->query('search');
            $query->where('nome', 'like', "%{$search}%");
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', (string) $request->query('categoria'));
        }

        if ($request->filled('min_preco')) {
            $minPreco = (float) $request->query('min_preco');
            $query->whereHas('lotes', function ($loteQuery) use ($minPreco) {
                $loteQuery->where('preco_custo', '>=', $minPreco);
            });
        }

        if ($request->filled('max_preco')) {
            $maxPreco = (float) $request->query('max_preco');
            $query->whereHas('lotes', function ($loteQuery) use ($maxPreco) {
                $loteQuery->where('preco_custo', '<=', $maxPreco);
            });
        }

        $allowedSort = ['nome', 'preco_custo', 'created_at', 'preco'];
        $sortByInput = (string) $request->query('sort_by', 'created_at');
        if (!in_array($sortByInput, $allowedSort, true)) {
            $sortByInput = 'created_at';
        }

        $sortBy = $sortByInput === 'preco' ? 'preco_custo' : $sortByInput;
        $sortDir = strtolower((string) $request->query('sort_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        if ($sortBy === 'preco_custo') {
            $query->orderBy(
                LoteProduto::query()
                    ->select('preco_custo')
                    ->whereColumn('produto_id', 'produtos.id')
                    ->latest('id')
                    ->limit(1),
                $sortDir
            );
        } else {
            $query->orderBy($sortBy, $sortDir);
        }

        return response()->json($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'categoria' => ['nullable', 'string', 'max:255'],
            'lote' => ['required', 'string', 'max:255'],
            'validade' => ['required', 'date'],
            'preco_custo' => ['required', 'numeric', 'min:0'],
            'quantidade' => ['required', 'integer', 'min:0'],
        ]);

        $produto = Produto::create([
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'] ?? null,
            'categoria' => $validated['categoria'] ?? null,
        ]);

        $produto->lotes()->create([
            'lote' => $validated['lote'],
            'validade' => $validated['validade'],
            'preco_custo' => $validated['preco_custo'],
            'quantidade' => $validated['quantidade'],
        ]);

        return response()->json($produto->load('loteAtual'), 201);
    }

    public function show($id)
    {
        $produto = Produto::with('loteAtual')->findOrFail($id);
        return response()->json($produto);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'categoria' => ['nullable', 'string', 'max:255'],
            'lote' => ['required', 'string', 'max:255'],
            'validade' => ['required', 'date'],
            'preco_custo' => ['required', 'numeric', 'min:0'],
            'quantidade' => ['required', 'integer', 'min:0'],
        ]);

        $produto = Produto::findOrFail($id);

        $produto->update([
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'] ?? null,
            'categoria' => $validated['categoria'] ?? null,
        ]);

        $loteAtual = $produto->lotes()->latest('id')->first();

        if ($loteAtual) {
            $loteAtual->update([
                'lote' => $validated['lote'],
                'validade' => $validated['validade'],
                'preco_custo' => $validated['preco_custo'],
                'quantidade' => $validated['quantidade'],
            ]);
        } else {
            $produto->lotes()->create([
                'lote' => $validated['lote'],
                'validade' => $validated['validade'],
                'preco_custo' => $validated['preco_custo'],
                'quantidade' => $validated['quantidade'],
            ]);
        }

        return response()->json($produto->load('loteAtual'));
    }

    public function destroy($id)
    {
        Produto::destroy($id);
        return response()->json(['message' => 'Produto removido com sucesso!']);
    }
}
