<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        \App\Models\User::create([
            'name' => 'Usuario Root',
            'username' => 'root',
            'password' => Hash::make('ssa.audit'),
            'email' => 'root@localhost',
            'is_superuser' => 1,
            'avatar' => '/assets/avatars/50-king.svg'
        ]);

        $this->call(PermissionsTableSeeder::class);
    }
}
