<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        \DB::table('user_role_header')->insert([
            ['role_id' => 1, 'nama_role' => 'Superadmin', 'updated_date' => now(), 'active' => 1, 'deleted' => '0'],
            ['role_id' => 2, 'nama_role' => 'Admin Cabang', 'updated_date' => now(), 'active' => 1, 'deleted' => '0'],
            ['role_id' => 3, 'nama_role' => 'Kakak PA', 'updated_date' => now(), 'active' => 1, 'deleted' => '0'],
            ['role_id' => 4, 'nama_role' => 'Anak PA', 'updated_date' => now(), 'active' => 1, 'deleted' => '0'],
            ['role_id' => 5, 'nama_role' => 'Approval', 'updated_date' => now(), 'active' => 1, 'deleted' => '0'],
        ]);

        // 2. Seed Default Cabang
        \DB::table('cabang_header')->insert([
            'cabang_id' => 1,
            'nama_cabang' => 'Pusat',
            'updated_date' => now(),
            'active' => 1,
            'deleted' => '0'
        ]);

        // 3. Seed Superadmin User
        User::create([
            'nama' => 'Super Admin Local',
            'email' => 'admin_local@sinodegkkd.org',
            'password' => bcrypt('password'),
            'lfk_role_id' => 1, // Superadmin
            'lfk_cabang_id' => 1,
            'active' => 1,
            'deleted' => '0',
        ]);
    }
}
