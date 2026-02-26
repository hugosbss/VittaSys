<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WebProdutoController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = (int) $request->query('per_page', 10);
        $perPage = max(5, min($perPage, 50));

        $query = Produto::query();

        if ($request->filled('search')) {
            $query->where('nome', 'like', '%' . $request->string('search') . '%');
        }

        if ($request->filled('categoria')) {
            $query->where('categoria', $request->string('categoria'));
        }

        if ($request->filled('min_preco')) {
            $query->where('preco', '>=', (float) $request->query('min_preco'));
        }

        if ($request->filled('max_preco')) {
            $query->where('preco', '<=', (float) $request->query('max_preco'));
        }

        $allowedSort = ['nome', 'preco', 'quantidade_estoque', 'created_at'];
        $sortBy = $request->query('sort_by', 'created_at');
        if (!in_array($sortBy, $allowedSort, true)) {
            $sortBy = 'created_at';
        }

        $sortDir = strtolower($request->query('sort_dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $produtos = $query->paginate($perPage)->withQueryString();

        return view('pages.produtos', [
            'produtos' => $produtos,
            'categorias' => Produto::query()
                ->whereNotNull('categoria')
                ->where('categoria', '!=', '')
                ->distinct()
                ->orderBy('categoria')
                ->pluck('categoria'),
            'hasSku' => Schema::hasColumn('produtos', 'sku'),
            'filters' => [
                'search' => (string) $request->query('search', ''),
                'categoria' => (string) $request->query('categoria', ''),
                'min_preco' => (string) $request->query('min_preco', ''),
                'max_preco' => (string) $request->query('max_preco', ''),
                'sort_by' => (string) $sortBy,
                'sort_dir' => (string) $sortDir,
                'per_page' => (string) $perPage,
            ],
        ]);
    }

    public function create(): View
    {
        return view('pages.cadastrar', [
            'categorias' => Produto::query()
                ->whereNotNull('categoria')
                ->where('categoria', '!=', '')
                ->distinct()
                ->orderBy('categoria')
                ->pluck('categoria'),
            'hasSku' => Schema::hasColumn('produtos', 'sku'),
            'ultimosProdutos' => Produto::latest()->take(6)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'preco' => ['required', 'numeric', 'min:0'],
            'quantidade_estoque' => ['required', 'integer', 'min:0'],
            'categoria' => ['nullable', 'string', 'max:255'],
            'categoria_nova' => ['nullable', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'imagem' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($this->hasSkuColumn()) {
            $request->validate([
                'sku' => ['nullable', 'string', 'max:100'],
            ]);
            $validated['sku'] = $request->string('sku')->toString();
        }

        $validated['categoria'] = $this->resolveCategoria($request, $validated['categoria'] ?? null);
        unset($validated['categoria_nova']);
        unset($validated['imagem']);

        if ($request->hasFile('imagem')) {
            $validated['imagem'] = $request->file('imagem')->store('produtos', 'public');
        }

        Produto::create($validated);

        return redirect()
            ->route('web.produtos.cadastrar')
            ->with('success', 'Produto cadastrado com sucesso.');
    }

    public function show(Produto $produto): View
    {
        return view('pages.produto-detalhe', [
            'produto' => $produto,
            'hasSku' => $this->hasSkuColumn(),
        ]);
    }

    public function edit(Produto $produto): View
    {
        return view('pages.produto-editar', [
            'produto' => $produto,
            'categorias' => Produto::query()
                ->whereNotNull('categoria')
                ->where('categoria', '!=', '')
                ->distinct()
                ->orderBy('categoria')
                ->pluck('categoria'),
            'hasSku' => $this->hasSkuColumn(),
        ]);
    }

    public function update(Request $request, Produto $produto): RedirectResponse
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'preco' => ['required', 'numeric', 'min:0'],
            'quantidade_estoque' => ['required', 'integer', 'min:0'],
            'categoria' => ['nullable', 'string', 'max:255'],
            'categoria_nova' => ['nullable', 'string', 'max:255'],
            'descricao' => ['nullable', 'string'],
            'imagem' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($this->hasSkuColumn()) {
            $request->validate([
                'sku' => ['nullable', 'string', 'max:100'],
            ]);
            $validated['sku'] = $request->string('sku')->toString();
        }

        $validated['categoria'] = $this->resolveCategoria($request, $validated['categoria'] ?? null);
        unset($validated['categoria_nova']);
        unset($validated['imagem']);

        if ($request->hasFile('imagem')) {
            if (!empty($produto->imagem)) {
                Storage::disk('public')->delete($produto->imagem);
            }
            $validated['imagem'] = $request->file('imagem')->store('produtos', 'public');
        }

        $produto->update($validated);

        return redirect()
            ->route('web.produtos.index')
            ->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy(Produto $produto): RedirectResponse
    {
        if (!empty($produto->imagem)) {
            Storage::disk('public')->delete($produto->imagem);
        }
        $produto->delete();

        return redirect()
            ->route('web.produtos.index')
            ->with('success', 'Produto removido com sucesso.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:produtos,id'],
        ]);

        Produto::whereIn('id', $validated['ids'])->delete();

        return redirect()
            ->route('web.produtos.index')
            ->with('success', 'Produtos selecionados removidos com sucesso.');
    }

    private function hasSkuColumn(): bool
    {
        return Schema::hasColumn('produtos', 'sku');
    }

    private function resolveCategoria(Request $request, ?string $fallback): ?string
    {
        $nova = trim($request->input('categoria_nova', ''));

        if ($nova !== '') {
            return $nova;
        }

        $categoria = trim((string) ($fallback ?? ''));

        return $categoria === '' ? null : $categoria;
    }
}
