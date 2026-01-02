<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminExist = User::where("email","admin@kelaskita.com")->exists();

        if (!$adminExist){
            User::create([
                'first_name'    =>  'Admin',
                'last_name'     =>  'Admin',
                'username'      =>  'admin',
                'email'         =>  'admin@kelaskita.com',
                'password'      =>  Hash::make('password'),
                'role'          =>  'admin',
                'deskripsi'     =>  'Admin',
                'foto_profil'   =>  'admin.png',
                'status'        =>  'active'
            ]);
            $this->command->info('Admin dibuat');
        }else{
            $this->command->info('Admin sudah ada');
        }
    }
}
