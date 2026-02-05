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
        User::factory()->create([
            'username' => 'admin',
            'name'     => 'Admin',
            'email'    => 'admin@ntc.local',
            'password' => 'password',
            'role'     => 'admin',
            'permissions' => 'view_logs,upload_docs,view_sip_docs,update_sip_docs,view_client_apps,update_client_apps,manage_users',
        ]);
    }
}
