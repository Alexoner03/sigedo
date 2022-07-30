<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Heading;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Business::create([
            'business_name' => 'SANABRIA & ASOCIADOS',
            'ruc' => '20555559748',
            'address' => 'Cal. German Schereiber Nro. 184 Int. 501',
            'contact_number' => '987654321',
            'heading_id' => 23
        ]);
    }
}
