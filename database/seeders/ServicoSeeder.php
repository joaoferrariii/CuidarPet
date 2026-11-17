<?php

namespace Database\Seeders;

use App\Models\Servico;
use Illuminate\Database\Seeder;

class ServicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicos = [
            ['descricao' => 'Consulta Clínica Geral'],
            ['descricao' => 'Aplicação de Vacina'],
            ['descricao' => 'Banho e Tosa Completa'],
            ['descricao' => 'Banho Simples'],
            ['descricao' => 'Corte de Unhas e Limpeza de Ouvido'],
            ['descricao' => 'Castração'],
            ['descricao' => 'Limpeza de Tártaro (Tartarectomia)'],
            ['descricao' => 'Exame de Sangue / Hemograma'],
        ];

        foreach ($servicos as $servico) {
            Servico::firstOrCreate(['descricao' => $servico['descricao']], $servico);
        }
    }
}
