<?php

namespace App\Models\Traits;

use App\Models\Reply;
use App\Models\Topic;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait ActiveUserHelper
{
    protected $users = [];

    protected $topicWeight = 4;
    protected $replyWeight = 1;
    protected $passDays = 7;
    protected $userNumbers = 6;

    protected $cacheKey = 'lara_bbs_active_users';
    protected $cacheExpires = 65 * 60;

    public function getActiveUsers()
    {
        return Cache::remember($this->cacheKey, $this->cacheExpires, function () {
           return $this->calculateActiveUsers();
        });
    }

    public function calculateAndCacheActiveUsers()
    {
        $activeUsers = $this->calculateActiveUsers();
        $this->cacheActiveUser($activeUsers);
    }

    /**
     * @return Collection
     */
    private function calculateActiveUsers(): Collection
    {
        $this->calculateTopicScore();
        $this->calculateReplyScore();

        $users = Arr::sort($this->users, function ($user) {
            return $user['score'];
        });

        $users = array_slice($users, 0, $this->userNumbers, true);
        $activeUsers = collect();
        foreach ($users as $userId => $user) {
            $user = $this->find($userId);
            if ($user) {
                $activeUsers->push($user);
            }
        }
        return $activeUsers;
    }

    /**
     * 计算用户话题的积分
     */
    private function calculateTopicScore()
    {
        // 从话题数据表里取出限定时间范围（$pass_days）内，有发表过话题的用户,并且同时取出用户此段时间内发布话题的数量
        $topicUsers = Topic::query()->select(DB::raw('user_id, count(*) as topic_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->passDays))
            ->groupBy('user_id')
            ->get();

        foreach ($topicUsers as $item) {
            $this->users[$item->user_id]['score'] = $item->topic_count * $this->topicWeight;
        }
    }

    /**
     * 计算用户回复帖子积分
     */
    private function calculateReplyScore()
    {
        $replyUsers = Reply::query()->select(DB::raw('user_id, count(*) as reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->passDays))
            ->groupBy('user_id')
            ->get();

        foreach ($replyUsers as $item) {
            $replyScore = $item->reply_count * $this->replyWeight;
            if (isset($this->users[$item->user_id])) {
                $this->users[$item->user_id]['score'] += $replyScore;
            } else {
                $this->users[$item->user_id]['score'] = $replyScore;
            }
        }
    }

    private function cacheActiveUser($activeUsers)
    {
        Cache::put($this->cacheKey, $activeUsers, $this->cacheExpires);
    }
}
