<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Centro;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $centro = Centro::first() ?? Centro::factory()->create(['nombre' => 'Centroliza']);
        
        User::create([
            'centro_id' => $centro->id,
            'name' => 'Director Test',
            'email' => 'director@test.com',
            'dni' => '00000001D',
            'role' => 'director',
            'status' => 1,
            'password' => bcrypt('password')
        ])->assignRole('director');
            
        User::create([
            'centro_id' => $centro->id,
            'name' => 'Profesor Test',
            'email' => 'profesor@test.com',
            'dni' => '00000002P',
            'role' => 'profesor',
            'status' => 1,
            'password' => bcrypt('password')
        ])->assignRole('profesor');
            
        User::create([
            'centro_id' => $centro->id,
            'name' => 'Alumno Test 2',
            'email' => 'alumno2@test.com',
            'dni' => '12345679A',
            'role' => 'alumno',
            'status' => 1,
            'password' => bcrypt('password')
        ])->assignRole('alumno');
    }
}
