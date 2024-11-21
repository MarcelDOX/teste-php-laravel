<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Adiciona o Seeder de Categorias
        $this->call([
            CategorySeeder::class,
            // DocumentSeeder::class,
        ]);
    }
}
