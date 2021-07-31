<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'description' => 'ADMINSITRADOR'
        ]);
        Role::create([
            'description' => 'TRABAJADOR'
        ]);
        Role::create([
            'description' => 'TRABAJADOR CLIENTE'
        ]);
    }
}
