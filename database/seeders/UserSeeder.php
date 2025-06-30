<?php

/*
sudo ./vendor/bin/sail artisan migrate:reset;sudo ./vendor/bin/sail artisan migrate;sudo ./vendor/bin/sail artisan db:seed;
*/

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                "name" => "ユーザー$i",
                "email" => "user$i@example.com",
                "password" => \Hash::make("password")
            ]);
        }

        User::create([
            "name" => "髙木 正宗",
            "email" => "Takagi.Masamune@example.com",
            "password" => \Hash::make("password")
        ]);

        User::create([
            "name" => "髙木 親宗",
            "email" => "Takagi.Oyamune@example.com",
            "password" => \Hash::make("password")
        ]);

        User::create([
            "name" => "戸蘭 鈴雄",
            "email" => "Toran.Suzuo@example.com",
            "password" => \Hash::make("password")
        ]);

        User::create([
            "name" => "越雲 澄子",
            "email" => "Kosumo.Sumiko@example.com",
            "password" => \Hash::make("password")
        ]);


    }
}
