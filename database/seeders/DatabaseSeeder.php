<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory()->create([
             'name' => 'user1',
             'email' => 'user1@gmail.com',
             'password' => Hash::make('12345678'),
             ]
         );

        User::factory()->create([
                'name' => 'user2',
                'email' => 'user2@gmail.com',
                'password' => Hash::make('12345678'),
            ]
        );

         Todo::factory(2)->create(
             [
                 'user_id' => 1
             ]
         );

        Todo::factory(3)->create(
            [
                'user_id' => 2
            ]
        );
    }
}
