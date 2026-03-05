<?php

namespace Database\Seeders;

use App\Infrastructure\Database\Models\ModelUser;
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
        // ModelUser::factory(10)->create();

        ModelUser::factory()->create([
            'name' => 'Test ModelUser',
            'email' => 'test@example.com',
        ]);
    }
}

