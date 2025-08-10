<?php

namespace Database\Seeders;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Src\Blog\Articles\Model\ArticleModel;
use Src\Authorization\Users\Domain\UserModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        //UserModel::factory()->count(20)->create();
        $user1 = UserModel::create([
            'name'           => 'Admin',
            'email'          => 'admin@bagsandtea.com',
            'remember_token' => Str::random(60),
            'email_verified_at' => Carbon::now(),
            'password'       =>  Hash::make("3?Eaj-6dj4DS_sO27"),

        ]);

        $user1 = UserModel::create([
            'name'           => 'Nicolás',
            'email'          => 'nicolas.tabares.tech@gmail.com',
            'remember_token' => Str::random(60),
            'email_verified_at' => Carbon::now(),
            'password'       =>  Hash::make("3?Eaj-6dj4DS_sO27"),

        ]);
    }
}
