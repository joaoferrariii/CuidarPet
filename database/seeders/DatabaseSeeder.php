<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Papéis de acesso (Admin e Funcionário)
        $this->call(RoleSeeder::class);

        // 2. Tabelas auxiliares
        $this->call(FormaPagamentoSeeder::class);
        $this->call(VacinaSeeder::class);
        $this->call(ServicoSeeder::class);

        // 3. Usuários do sistema
        $adminRole = Role::where('nome', 'Admin')->first();
        $funcionarioRole = Role::where('nome', 'Funcionário')->first();

        User::firstOrCreate(
            ['email' => 'admin@cuidarpet.com'],
            [
                'name' => 'Administrador CuidarPet',
                'password' => Hash::make('password'),
                'role_id' => $adminRole?->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'funcionario@cuidarpet.com'],
            [
                'name' => 'Funcionário CuidarPet',
                'password' => Hash::make('password'),
                'role_id' => $funcionarioRole?->id,
            ]
        );

        // 4. Clientes, Pets, Vacinas aplicadas e Registros de Serviço
        $this->call(ClinicaSeeder::class);
    }
}
