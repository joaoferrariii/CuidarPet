<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\FormaPagamento;
use App\Models\Pet;
use App\Models\RegistroServico;
use App\Models\Servico;
use App\Models\Vacina;
use App\Models\Vacinacao;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ClinicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vacinaV8 = Vacina::where('descricao', 'like', '%V8%')->first();
        $vacinaRaiva = Vacina::where('descricao', 'like', '%Antirrábica%')->first();
        $vacinaFelina = Vacina::where('descricao', 'like', '%Felina%')->first();

        $servicoConsulta = Servico::where('descricao', 'like', '%Consulta%')->first();
        $servicoBanho = Servico::where('descricao', 'like', '%Banho e Tosa%')->first();
        $servicoVacina = Servico::where('descricao', 'like', '%Aplicação de Vacina%')->first();

        $pix = FormaPagamento::where('descricao', 'PIX')->first();
        $cartaoCredito = FormaPagamento::where('descricao', 'like', '%Crédito%')->first();
        $dinheiro = FormaPagamento::where('descricao', 'Dinheiro')->first();

        // 1. Cliente 1
        $cliente1 = Cliente::firstOrCreate(
            ['cpf' => '12345678901'],
            [
                'nome' => 'Ana Clara Silveira',
                'email' => 'anaclara@email.com',
                'endereco' => 'Rua das Flores, 120 - Centro',
            ]
        );

        $pet1 = Pet::firstOrCreate(
            ['cliente_id' => $cliente1->id, 'nome' => 'Thor'],
            [
                'raca' => 'Golden Retriever',
                'data_nascimento' => Carbon::now()->subYears(3)->format('Y-m-d'),
                'genero' => 'M',
                'castrado' => true,
            ]
        );

        $pet2 = Pet::firstOrCreate(
            ['cliente_id' => $cliente1->id, 'nome' => 'Luna'],
            [
                'raca' => 'Siamês',
                'data_nascimento' => Carbon::now()->subYears(2)->format('Y-m-d'),
                'genero' => 'F',
                'castrado' => true,
            ]
        );

        // 2. Cliente 2
        $cliente2 = Cliente::firstOrCreate(
            ['cpf' => '98765432100'],
            [
                'nome' => 'Carlos Eduardo Ribeiro',
                'email' => 'carlos.ribeiro@email.com',
                'endereco' => 'Av. Brasil, 450 - Jardim América',
            ]
        );

        $pet3 = Pet::firstOrCreate(
            ['cliente_id' => $cliente2->id, 'nome' => 'Bob'],
            [
                'raca' => 'SRD (Vira-lata)',
                'data_nascimento' => Carbon::now()->subYears(1)->subMonths(4)->format('Y-m-d'),
                'genero' => 'M',
                'castrado' => false,
            ]
        );

        // 3. Cliente 3
        $cliente3 = Cliente::firstOrCreate(
            ['cpf' => '45678912344'],
            [
                'nome' => 'Mariana Oliveira Souza',
                'email' => 'mariana.souza@email.com',
                'endereco' => 'Rua Minas Gerais, 89 - Bairro Novo',
            ]
        );

        $pet4 = Pet::firstOrCreate(
            ['cliente_id' => $cliente3->id, 'nome' => 'Amora'],
            [
                'raca' => 'Shih Tzu',
                'data_nascimento' => Carbon::now()->subMonths(8)->format('Y-m-d'),
                'genero' => 'F',
                'castrado' => false,
            ]
        );

        // Vacinações para os pets
        if ($vacinaV8 && $pet1) {
            Vacinacao::firstOrCreate(
                ['pet_id' => $pet1->id, 'vacina_id' => $vacinaV8->id, 'data' => Carbon::now()->subMonths(6)->toDateString()],
                ['validade' => Carbon::now()->addMonths(6)->toDateString()]
            );
        }

        if ($vacinaRaiva && $pet1) {
            Vacinacao::firstOrCreate(
                ['pet_id' => $pet1->id, 'vacina_id' => $vacinaRaiva->id, 'data' => Carbon::now()->subMonths(3)->toDateString()],
                ['validade' => Carbon::now()->addMonths(9)->toDateString()]
            );
        }

        if ($vacinaFelina && $pet2) {
            Vacinacao::firstOrCreate(
                ['pet_id' => $pet2->id, 'vacina_id' => $vacinaFelina->id, 'data' => Carbon::now()->subMonths(4)->toDateString()],
                ['validade' => Carbon::now()->addMonths(8)->toDateString()]
            );
        }

        // Registros de serviços realizados
        if ($servicoConsulta && $pix && $pet1) {
            RegistroServico::firstOrCreate(
                ['pet_id' => $pet1->id, 'servico_id' => $servicoConsulta->id, 'data' => Carbon::now()->subDays(15)->toDateString()],
                ['forma_pagamento_id' => $pix->id]
            );
        }

        if ($servicoBanho && $cartaoCredito && $pet4) {
            RegistroServico::firstOrCreate(
                ['pet_id' => $pet4->id, 'servico_id' => $servicoBanho->id, 'data' => Carbon::now()->subDays(2)->toDateString()],
                ['forma_pagamento_id' => $cartaoCredito->id]
            );
        }

        if ($servicoVacina && $dinheiro && $pet3) {
            RegistroServico::firstOrCreate(
                ['pet_id' => $pet3->id, 'servico_id' => $servicoVacina->id, 'data' => Carbon::now()->subDays(5)->toDateString()],
                ['forma_pagamento_id' => $dinheiro->id]
            );
        }
    }
}
