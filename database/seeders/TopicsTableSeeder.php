<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;

class TopicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 所有用户ID数组
        $userIds = User::all()->pluck('id')->toArray();
        // 所有分类ID数组
        $categories = Category::all()->pluck('id')->toArray();

        $faker = app(Generator::class);

        $topics = Topic::factory()->count(100)->make()->each(function ($topic, $index) use ($userIds, $categories, $faker) {
            /** @var Generator $faker */
            $topic->user_id = $faker->randomElement($userIds);
            $topic->category_id = $faker->randomElement($categories);
        });

        Topic::insert($topics->toArray());
    }
}
