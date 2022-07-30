<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

        User::create([
            'email' => 'administrador@sigedo.estudiosanabria.com',
            'name' => 'ADMINISTRADOR',
            'dni' => '00000000',
            'email_verified_at' => now(),
            'role_id' => 1,
            'position_id' => 1,
            'business_id' => 1,
            'password' => Hash::make('s1gedoAdministrador'), // password
            'remember_token' => 'FgYR4o6m0H',
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
