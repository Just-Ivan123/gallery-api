<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Gallery;
use App\Models\Image;
use App\Models\Comment;

class UserSeeder extends Seeder
{
    public static function run()
    {
        // Создаем 10 пользователей с помощью фабрики User
        User::factory(10)->create();
    }
}