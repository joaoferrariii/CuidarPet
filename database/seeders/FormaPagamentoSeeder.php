<?php

namespace Database\Seeders;

use App\Models\FormaPagamento;
use Illuminate\Database\Seeder;

class FormaPagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formas = [
            ['descricao' => 'Dinheiro'],
            ['descricao' => 'Cartão de Crédito'],
            ['descricao' => 'Cartão de Débito'],
            ['descricao' => 'PIX'],
        ];

        foreach ($formas as $forma) {
            FormaPagamento::firstOrCreate(['descricao' => $forma['descricao']], $forma);
        }
    }
}
