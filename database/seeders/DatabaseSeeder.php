<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Packer\Packer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('oauth_clients')->insert([
            'id'                     => 2,
            'name'                   => 'Laravel Password Grant Client',
            'secret'                 => 'xJaJ3xfq2H1yvZkaCwR2H3XTwBrj37r8bxITj6Il',
            'provider'               => 'users',
            'redirect'               => 'http://localhost',
            'personal_access_client' => 0,
            'revoked'                => 0,
            'password_client'        => 1,
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);
        DB::table('oauth_clients')->insert([
            'id'                     => 3,
            'name'                   => 'Laravel UserId Grant Client',
            'secret'                 => 'ny4lkzI0jJj6RXMct5zzUrBSmi6GIggHYWwW6RJI',
            'provider'               => 'packer',
            'redirect'               => 'http://localhost',
            'personal_access_client' => 0,
            'revoked'                => 0,
            'password_client'        => 0,
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);

        User::factory()->create([
            'name'              => 'okay.km.ua@gmail.com',
            'email'              => 'okay.km.ua@gmail.com',
            'password'          => Hash::make('A4sven31071992'),
            'email_verified_at' => now(),
        ]);

        Packer::create([
           'name' => 'Test',
           'user_id' => Hash::make('user_id')
        ]);
    }
}
