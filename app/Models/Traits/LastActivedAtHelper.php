<?php


namespace App\Models\Traits;


use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

trait LastActivedAtHelper
{
    protected $hashPrefix = 'bbs_last_actived_at_';
    protected $fieldPrefix = 'user_';

    /**
     * 记录用户的最后登录时间
     */
    public function recordLastActivedAt()
    {
        $hashKey = $this->getHashFromDateString(Carbon::now()->toDateString());
        $filedKey = $this->getHashField();
        $now = Carbon::now()->toDateTimeString();
        Redis::hSet($hashKey, $filedKey, $now);
    }


    /**
     * 同步用户最后登录时间，从redis同步到数据库中
     */
    public function syncUserActivedAt()
    {
        // 获取昨天的日期，格式如：2021-05-19
        $hashKey = $this->getHashFromDateString(Carbon::yesterday()->toDateString());
        $datas = Redis::hGetAll($hashKey);
        foreach ($datas as $user_id => $actived_at) {
            // 会将 `user_1` 转换为 1
            $user_id = str_replace($this->fieldPrefix, '', $user_id);
            // 只有当用户存在时才更新到数据库中
            if ($user= $this->find($user_id)) {
                $user->last_actived_at = $actived_at;
                $user->save();
            }
            // 以数据库为中心的存储，既已同步，即可删除
            Redis::del($hashKey);
        }
    }

    /**
     * @param $value
     * @return Carbon
     */
    public function getLastActivedAtAttribute($value): Carbon
    {
        // 获取今天的日期
        $hashKey = $this->getHashFromDateString(Carbon::now()->toDateString());
        $filedKey = $this->getHashField();
        $dateTime = Redis::hGet($hashKey, $filedKey) ? : $value;
        // 如果存在的话，返回时间对应的 Carbon 实体
        if ($dateTime) {
            return new Carbon($dateTime);
        } else {
            // 否则使用用户注册时间
            return $this->created_at;
        }
    }

    /**
     * @param $date
     * @return string
     */
    public function getHashFromDateString($date)
    {
        // Redis 哈希表的命名，如：larabbs_last_actived_at_2017-10-21
        return $this->hashPrefix . $date;
    }

    /**
     * @return string
     */
    public function getHashField()
    {
        // 字段名称，如：user_1
        return $this->fieldPrefix . $this->id;
    }
}
