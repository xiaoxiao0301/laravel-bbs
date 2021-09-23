<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()->count(10)->make()->makeVisible(['password', 'remember_token'])->toArray();
        User::insert($users);
        // 单独处理第一个用户的数据
        $user = User::find(1);
        $user->name = 'Jack';
        $user->email = 'jack@example.com';
        $user->avatar = 'https://cdn.learnku.com/uploads/images/201710/14/1/ZqM7iaP4CR.png';
        $user->save();
    }
}
