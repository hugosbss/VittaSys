<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 10);
        $perPage = max(1, min($perPage, 100));

        $query = Produto::query();

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where('nome', 'like', "%{$search}%");
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->query('categoria'));
        }

        if ($request->filled('min_preco')) {
            $query->where('preco', '>=', $request->query('min_preco'));
        }

        if ($request->filled('max_preco')) {
            $query->where('preco', '<=', $request->query('max_preco'));
        }

        $allowedSort = ['nome', 'preco', 'created_at'];
        $sortBy = $request->query('sort_by', 'created_at');
        if (!in_array($sortBy, $allowedSort, true)) {
            $sortBy = 'created_at';
        }

        $sortDir = strtolower($request->query('sort_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        if ($sortBy === 'preco') {
            $produtos = $query->orderByRaw("CAST(preco AS DECIMAL(10,2)) {$sortDir}")->paginate($perPage);
        } else {
            $produtos = $query->orderBy($sortBy, $sortDir)->paginate($perPage);
        }

        return response()->json($produtos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'preco' => ['required', 'numeric', 'min:0'],
            'descricao' => ['nullable', 'string'],
            'categoria' => ['nullable', 'string', 'max:255'],
            'quantidade_estoque' => ['nullable', 'integer', 'min:0'],
        ]);

        $produto = Produto::create($validated);
        return response()->json($produto, 201);
    }

    public function show($id)
    {
        $produto = Produto::findOrFail($id);
        return response()->json($produto);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'preco' => ['required', 'numeric', 'min:0'],
            'descricao' => ['nullable', 'string'],
            'categoria' => ['nullable', 'string', 'max:255'],
            'quantidade_estoque' => ['nullable', 'integer', 'min:0'],
        ]);

        $produto = Produto::findOrFail($id);
        $produto->update($validated);
        return response()->json($produto);
    }

    public function destroy($id)
    {
        Produto::destroy($id);
        return response()->json(['message' => 'Produto removido com sucesso!']);
    }
}
