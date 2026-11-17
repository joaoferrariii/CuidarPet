<?php

namespace App\Http\Controllers;

use App\Http\Requests\PetRequest;
use App\Models\Cliente;
use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $pets = Pet::query()
            ->search($request->input('search'))
            ->with('cliente')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Pets/Index', [
            'pets' => $pets,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Pets/Create', [
            'clientes' => Cliente::select('id', 'nome', 'cpf')->orderBy('nome')->get(),
            'cliente_id' => $request->integer('cliente_id') ?: null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PetRequest $request): RedirectResponse
    {
        $pet = Pet::create($request->validated());

        return redirect()->route('pets.show', $pet)
            ->with('success', 'Pet cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet): Response
    {
        $pet->load([
            'cliente',
            'vacinacoes.vacina',
            'registrosServicos.servico',
            'registrosServicos.formaPagamento',
        ]);

        return Inertia::render('Pets/Show', [
            'pet' => $pet,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pet $pet): Response
    {
        return Inertia::render('Pets/Edit', [
            'pet' => $pet,
            'clientes' => Cliente::select('id', 'nome', 'cpf')->orderBy('nome')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PetRequest $request, Pet $pet): RedirectResponse
    {
        $pet->update($request->validated());

        return redirect()->route('pets.show', $pet)
            ->with('success', 'Pet atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet): RedirectResponse
    {
        $pet->delete();

        return redirect()->route('pets.index')
            ->with('success', 'Pet removido com sucesso!');
    }
}
