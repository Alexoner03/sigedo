<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // $businesses = Business::all();
        // $positions = Position::all();

        User::factory(1)->create([
            'email' => 'administrador@sigedo.test'
        ]);

        // foreach ($businesses as $business) {
            
        //     if($business->id === 1){

        //        foreach ($positions as $position) {
        //             User::factory(20)->create([
        //                 'role_id' => 2,
        //                 'position_id' => $position->id,
        //                 'business_id' => $business->id,
        //             ]);
        //        }

        //     }else{

        //         foreach ($positions as $position) {

        //             if($position->id !== 5)
        //             {
        //                 User::factory(20)->create([
        //                     'role_id' => 3,
        //                     'position_id' => $position->id,
        //                     'business_id' => $business->id,
        //                 ]);
        //             }
        //        } 
                
        //     }
        // }
    }
}
