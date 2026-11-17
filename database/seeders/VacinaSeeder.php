<?php

namespace Database\Seeders;

use App\Models\Vacina;
use Illuminate\Database\Seeder;

class VacinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vacinas = [
            ['descricao' => 'V8 / V10 (Múltipla Canina)'],
            ['descricao' => 'Antirrábica (Cães e Gatos)'],
            ['descricao' => 'Tríplice Felina (Panleucopenia, Rinotraqueíte e Calicivirose)'],
            ['descricao' => 'Quádrupla Felina (Tríplice + Clamidiose)'],
            ['descricao' => 'Gripe Canina (Tosse dos Canis)'],
            ['descricao' => 'Giardíase'],
            ['descricao' => 'Leishmaniose Canina'],
        ];

        foreach ($vacinas as $vacina) {
            Vacina::firstOrCreate(['descricao' => $vacina['descricao']], $vacina);
        }
    }
}
