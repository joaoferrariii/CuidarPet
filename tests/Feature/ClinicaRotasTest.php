<?php

use App\Models\Cliente;
use App\Models\FormaPagamento;
use App\Models\Pet;
use App\Models\Role;
use App\Models\Servico;
use App\Models\User;
use App\Models\Vacina;
use App\Models\Vacinacao;
use App\Models\RegistroServico;
use Inertia\Testing\AssertableInertia as Assert;

// --------------------------------------------------------------------------
// 1. SEGURANÇA E AUTENTICAÇÃO
// --------------------------------------------------------------------------
test('visitantes não autenticados são redirecionados para o login ao acessar rotas protegidas', function () {
    $this->get('/clientes')->assertRedirect('/login');
    $this->get('/pets')->assertRedirect('/login');
    $this->get('/vacinacoes')->assertRedirect('/login');
    $this->get('/registros-servicos')->assertRedirect('/login');
});

// --------------------------------------------------------------------------
// 2. ROTAS DE CLIENTES
// --------------------------------------------------------------------------
describe('Módulo de Clientes', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    });

    test('pode listar clientes e receber o componente Inertia Clientes/Index', function () {
        Cliente::create([
            'nome' => 'Ana Clara Silveira',
            'cpf' => '12345678901',
            'email' => 'anaclara@teste.com',
            'endereco' => 'Rua das Flores, 120',
        ]);

        $response = $this->get('/clientes');

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Clientes/Index')
                ->has('clientes.data', 1)
                ->where('clientes.data.0.nome', 'Ana Clara Silveira')
            );
    });

    test('pode cadastrar um novo cliente via POST /clientes', function () {
        $dados = [
            'nome' => 'Carlos Eduardo',
            'cpf' => '98765432100',
            'email' => 'carlos@teste.com',
            'endereco' => 'Av. Brasil, 450',
        ];

        $response = $this->post('/clientes', $dados);

        $cliente = Cliente::where('cpf', '98765432100')->first();
        expect($cliente)->not->toBeNull();

        $response->assertRedirect(route('clientes.show', $cliente));
        $this->assertDatabaseHas('clientes', ['email' => 'carlos@teste.com']);
    });

    test('falha ao cadastrar cliente com CPF duplicado (validação)', function () {
        Cliente::create([
            'nome' => 'Existente',
            'cpf' => '11122233344',
            'email' => 'existente@teste.com',
            'endereco' => 'Rua A, 1',
        ]);

        $response = $this->post('/clientes', [
            'nome' => 'Novo com mesmo CPF',
            'cpf' => '11122233344',
            'email' => 'outro@teste.com',
            'endereco' => 'Rua B, 2',
        ]);

        $response->assertSessionHasErrors(['cpf']);
    });

    test('pode atualizar dados de um cliente existente', function () {
        $cliente = Cliente::create([
            'nome' => 'Nome Antigo',
            'cpf' => '22233344455',
            'email' => 'antigo@teste.com',
            'endereco' => 'Rua Antiga, 10',
        ]);

        $response = $this->put("/clientes/{$cliente->id}", [
            'nome' => 'Nome Atualizado',
            'cpf' => '22233344455',
            'email' => 'atualizado@teste.com',
            'endereco' => 'Rua Nova, 20',
        ]);

        $response->assertRedirect(route('clientes.show', $cliente));
        $this->assertDatabaseHas('clientes', ['nome' => 'Nome Atualizado']);
    });

    test('pode remover um cliente', function () {
        $cliente = Cliente::create([
            'nome' => 'Para Excluir',
            'cpf' => '99988877766',
            'email' => 'excluir@teste.com',
            'endereco' => 'Rua X, 99',
        ]);

        $response = $this->delete("/clientes/{$cliente->id}");

        $response->assertRedirect(route('clientes.index'));
        $this->assertDatabaseMissing('clientes', ['id' => $cliente->id]);
    });
});

// --------------------------------------------------------------------------
// 3. ROTAS DE PETS
// --------------------------------------------------------------------------
describe('Módulo de Pets', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->cliente = Cliente::create([
            'nome' => 'Tutor Teste',
            'cpf' => '33344455566',
            'email' => 'tutor@teste.com',
            'endereco' => 'Rua Central, 50',
        ]);
    });

    test('pode cadastrar um pet vinculado a um cliente via POST /pets', function () {
        $dadosPet = [
            'cliente_id' => $this->cliente->id,
            'nome' => 'Thor',
            'raca' => 'Golden Retriever',
            'data_nascimento' => '2022-05-10',
            'genero' => 'M',
            'castrado' => true,
        ];

        $response = $this->post('/pets', $dadosPet);

        $pet = Pet::where('nome', 'Thor')->first();
        expect($pet)->not->toBeNull();

        $response->assertRedirect(route('pets.show', $pet));
        $this->assertDatabaseHas('pets', [
            'nome' => 'Thor',
            'cliente_id' => $this->cliente->id,
            'castrado' => 1,
        ]);
    });

    test('pode visualizar detalhes do pet com seu tutor carregado', function () {
        $pet = Pet::create([
            'cliente_id' => $this->cliente->id,
            'nome' => 'Luna',
            'raca' => 'Siamês',
            'data_nascimento' => '2023-01-15',
            'genero' => 'F',
            'castrado' => false,
        ]);

        $response = $this->get("/pets/{$pet->id}");

        $response->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page
                ->component('Pets/Show')
                ->where('pet.nome', 'Luna')
                ->where('pet.cliente.nome', 'Tutor Teste')
            );
    });
});

// --------------------------------------------------------------------------
// 4. ROTAS DE VACINAÇÃO E ATENDIMENTOS (SERVIÇOS)
// --------------------------------------------------------------------------
describe('Módulo de Vacinações e Atendimentos', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->cliente = Cliente::create([
            'nome' => 'Cliente Clínica',
            'cpf' => '55566677788',
            'email' => 'clinica@teste.com',
            'endereco' => 'Rua dos Pets, 10',
        ]);

        $this->pet = Pet::create([
            'cliente_id' => $this->cliente->id,
            'nome' => 'Bob',
            'raca' => 'SRD',
            'data_nascimento' => '2021-08-20',
            'genero' => 'M',
            'castrado' => true,
        ]);

        $this->vacina = Vacina::firstOrCreate(['descricao' => 'V8 Canina']);
        $this->servico = Servico::firstOrCreate(['descricao' => 'Banho e Tosa']);
        $this->forma = FormaPagamento::firstOrCreate(['descricao' => 'PIX']);
    });

    test('pode registrar uma vacinação via POST /vacinacoes', function () {
        $dados = [
            'pet_id' => $this->pet->id,
            'vacina_id' => $this->vacina->id,
            'data' => now()->toDateString(),
            'validade' => now()->addYear()->toDateString(),
        ];

        $response = $this->post('/vacinacoes', $dados);

        $response->assertRedirect(route('pets.show', $this->pet->id));
        $this->assertDatabaseHas('vacinacoes', [
            'pet_id' => $this->pet->id,
            'vacina_id' => $this->vacina->id,
        ]);
    });

    test('pode registrar um atendimento de serviço via POST /registros-servicos', function () {
        $dados = [
            'pet_id' => $this->pet->id,
            'servico_id' => $this->servico->id,
            'forma_pagamento_id' => $this->forma->id,
            'data' => now()->toDateString(),
        ];

        $response = $this->post('/registros-servicos', $dados);

        $response->assertRedirect(route('pets.show', $this->pet->id));
        $this->assertDatabaseHas('registros_servicos', [
            'pet_id' => $this->pet->id,
            'servico_id' => $this->servico->id,
            'forma_pagamento_id' => $this->forma->id,
        ]);
    });
});