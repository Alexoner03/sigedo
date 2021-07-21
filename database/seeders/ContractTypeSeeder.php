<?php

namespace Database\Seeders;

use App\Models\ContractType;
use Illuminate\Database\Seeder;

class ContractTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ContractType::create([
            'description' => "COMERCIAL"
        ]);
        ContractType::create([
            'description' => "LOGÍSTICO"
        ]);
        ContractType::create([
            'description' => "FINANCIERO"
        ]);
        ContractType::create([
            'description' => "LABORAL"
        ]);
        ContractType::create([
            'description' => "OTROS"
        ]);

    }
}
