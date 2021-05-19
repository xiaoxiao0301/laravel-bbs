<?php

namespace Database\Seeders;

use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;

class ReplysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 所有用户 ID 数组，如：[1,2,3,4]
        $userIds = User::all()->pluck('id')->toArray();
        // 所有话题 ID 数组，如：[1,2,3,4]
        $topicIds = Topic::all()->pluck('id')->toArray();
        $faker = app(Generator::class);
        $replies = Reply::factory()->count(1000)->make()->each(function ($reply, $index) use($userIds, $topicIds, $faker) {
            /** @var Generator $faker */
            $reply->user_id = $faker->randomElement($userIds);
            $reply->topic_id = $faker->randomElement($topicIds);
        });
        Reply::insert($replies->toArray());

    }
}
