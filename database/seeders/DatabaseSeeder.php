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
        DB::table('conf_anggaran')->insert([
            'id' => 1,
            'tahun' => date('Y'),
        	'anggaran_awal' => '2000000000',
        	'anggaran_sisa' => '2000000000',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        DB::table('m_kategori')->insert([
            'id' => 1,
        	'nama_kategori' => 'KBKI 1',
            'created_at' => date('Y-m-d H:i:s')
        ]);

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
        
        DB::table('conf_unit_kerja')->insert([
            'id' => 1,
        	'nama_unit_kerja' => 'Kepala Sekolah',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        DB::table('m_user_grup')->insert([
            'id' => 2,
        	'nama_grup' => 'Perencanaan',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        DB::table('m_user')->insert([
        	'id_user_grup' => '2',
        	'id_unit_kerja' => '1',
        	'username' => 'perencanaan',
        	'password' => md5('perencanaan'),
            'nama' => 'Perencanaan',
            'email' => 'hendrytime2beat@gmail.com',
            'no_hp' => '08987375213',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        DB::table('m_user_grup')->insert([
            'id' => 3,
        	'nama_grup' => 'Pengajuan',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        
        DB::table('m_user')->insert([
        	'id_user_grup' => '3',
        	'id_unit_kerja' => '1',
        	'username' => 'pengajuan',
        	'password' => md5('pengajuan'),
            'nama' => 'Pengajuan',
            'email' => 'hendrytime2beat@gmail.com',
            'no_hp' => '08987375213',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        
        DB::table('m_user_grup')->insert([
            'id' => 4,
        	'nama_grup' => 'Realisasi',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        DB::table('m_user')->insert([
        	'id_user_grup' => '4',
        	'username' => 'realisasi',
        	'password' => md5('realisasi'),
            'nama' => 'Realisasi',
            'email' => 'hendrytime2beat@gmail.com',
            'no_hp' => '08987375213',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        DB::table('m_user_grup')->insert([
            'id' => 5,
        	'nama_grup' => 'Penerimaan',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        DB::table('m_user')->insert([
        	'id_user_grup' => '5',
        	'username' => 'penerimaan',
        	'password' => md5('penerimaan'),
            'nama' => 'Penerimaan',
            'email' => 'hendrytime2beat@gmail.com',
            'no_hp' => '08987375213',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
