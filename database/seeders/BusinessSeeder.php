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
        $headings = Heading::all();

        Business::create([
            'business_name' => 'SANABRIA & ASOCIADOS',
            'ruc' => '12345678910',
            'address' => 'Una Calle Cualquiera',
            'contact_number' => '987654321',
            'heading_id' => 23
        ]);

        // foreach ($headings as $heading) {
        //     Business::factory(5)->create([
        //         'heading_id' => $heading->id
        //     ]);
        // }
    }
}
