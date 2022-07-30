<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Position::create([
            'description' => 'SANBRIA & ASOCIADOS'
        ]);

        Position::create([
            'description' => 'GERENCIA GENERAL'
        ]);

        Position::create([
            'description' => 'GERENCIA COMERCIAL'
        ]);
        Position::create([
            'description' => 'GERENCIA LOGISTICA'
        ]);
        Position::create([
            'description' => 'GERENCIA RRHH'
        ]);
        Position::create([
            'description' => 'OTRO'
        ]);
    }
}
