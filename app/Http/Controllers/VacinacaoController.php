<?php

namespace App\Http\Controllers;

use App\Http\Requests\VacinacaoRequest;
use App\Models\Pet;
use App\Models\Vacina;
use App\Models\Vacinacao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VacinacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $vacinacoes = Vacinacao::query()
            ->with(['pet.cliente', 'vacina'])
            ->latest('data')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Vacinacoes/Index', [
            'vacinacoes' => $vacinacoes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Vacinacoes/Create', [
            'pets' => Pet::select('id', 'nome', 'cliente_id')->with('cliente:id,nome')->orderBy('nome')->get(),
            'vacinas' => Vacina::select('id', 'descricao')->orderBy('descricao')->get(),
            'pet_id' => $request->integer('pet_id') ?: null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VacinacaoRequest $request): RedirectResponse
    {
        $vacinacao = Vacinacao::create($request->validated());
        
        // dd($vacinacao);
        return redirect()->route('pets.show', $vacinacao->pet_id)
            ->with('success', 'Vacinação registrada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacinacao $vacinacao): RedirectResponse
    {
        $vacinacao->delete();

        return redirect()->back()
            ->with('success', 'Registro de vacinação removido com sucesso!');
    }
}
