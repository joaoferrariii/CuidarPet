<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistroServicoRequest;
use App\Models\FormaPagamento;
use App\Models\Pet;
use App\Models\RegistroServico;
use App\Models\Servico;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RegistroServicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $registros = RegistroServico::query()
            ->with(['pet.cliente', 'servico', 'formaPagamento'])
            ->latest('data')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('RegistrosServicos/Index', [
            'registros' => $registros,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('RegistrosServicos/Create', [
            'pets' => Pet::select('id', 'nome', 'cliente_id')->with('cliente:id,nome')->orderBy('nome')->get(),
            'servicos' => Servico::select('id', 'descricao')->orderBy('descricao')->get(),
            'formasPagamento' => FormaPagamento::select('id', 'descricao')->orderBy('descricao')->get(),
            'pet_id' => $request->integer('pet_id') ?: null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegistroServicoRequest $request): RedirectResponse
    {
        $registro = RegistroServico::create($request->validated());

        return redirect()->route('pets.show', $registro->pet_id)
            ->with('success', 'Atendimento/serviço registrado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegistroServico $registroServico): RedirectResponse
    {
        $registroServico->delete();

        return redirect()->back()
            ->with('success', 'Registro de atendimento removido com sucesso!');
    }
}
