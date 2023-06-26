<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        
        DB::table('m_user_grup')->insert([
            'id' => 1,
        	'nama_grup' => 'Administrator',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        DB::table('m_user')->insert([
        	'id_user_grup' => '1',
        	'username' => 'admin',
        	'password' => md5('admin'),
            'nama' => 'Administrator',
            'email' => 'hendrytime2beat@gmail.com',
            'no_hp' => '08987375213',
            'created_at' => date('Y-m-d H:i:s')
        ]);

    }
}
