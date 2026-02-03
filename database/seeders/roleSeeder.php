<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class roleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'director']);
        Role::create(['name' => 'directivo']);
        Role::create(['name' => 'jefe_estudios']);
        Role::create(['name' => 'profesor']);
        Role::create(['name' => 'padre']);
        Role::create(['name' => 'alumno']);
    }
}
