<?php

namespace App\Models;

use App\Models\Traits\ActiveUserHelper;
use App\Models\Traits\LastActivedAtHelper;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use LastActivedAtHelper;
    use ActiveUserHelper;
    use HasFactory;
    use Notifiable {
        notify as protected laravelNotify;
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'introduction',
        'avatar',
        'weixin_openid',
        'weixin_unionid',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * 重写发送通知方法
     * @param $instance
     */
    public function notify($instance)
    {
        // 评论人是当前登录的用户不需要发送通知
        if ($this->id == Auth::id()) {
            return;
        }
        // 只有数据库类型通知才需提醒，直接发送 Email 或者其他的都 Pass
        if (method_exists($instance, 'toDatabase')) {
            // 用户未读数加1
            $this->increment('notification_count');
        }

        $this->laravelNotify($instance);
    }

    /**
     * 将所有未读消息设置为已读
     */
    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    /**
     * 用户关联话题模型，一个用户可以发表多篇话题，一对多
     * @return HasMany
     */
    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * 用户关联回复模型，一个用户可以发表多条评论，一对多
     * @return HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }


    /**
     * 权限校验
     * @param Model $model
     * @return bool
     */
    public function isAuthorOf(Model $model): bool
    {
        return $this->id == $model->user_id;
    }


    /**
     * 密码修改器
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        if (strlen($value) != 60) {
            // 不是重置密码是Hash:make加密
            $value = Hash::make($value);
        }
        $this->attributes['password'] = $value;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
