<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
            ServiceSeeder::class,
            DummyDataSeeder::class,
        ]);

        $users = [
            ['name' => 'Super Admin', 'email' => 'superadmin@man1kotabandung.sch.id', 'role' => 'super_admin'],
            ['name' => 'Admin',       'email' => 'admin@man1kotabandung.sch.id',       'role' => 'admin'],
            ['name' => 'Operator',    'email' => 'operator@man1kotabandung.sch.id',    'role' => 'operator'],
            ['name' => 'Editor',      'email' => 'editor@man1kotabandung.sch.id',      'role' => 'editor'],
            ['name' => 'Editor 2',    'email' => 'editor2@man1kotabandung.sch.id',     'role' => 'editor'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => bcrypt('sangatrahasia'),
                    'role' => $user['role'],
                ]
            );
        }
    }
}
