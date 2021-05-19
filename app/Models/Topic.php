<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'category_id',
        'excerpt',
        'slug'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * 一个话题属于一个分类，一对一关系
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 一个话题属于一个用户，一对一关系
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 一篇帖子下有多条回复
     * @return HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }


    /**
     * 按照创建时间排序
     * @param Builder $query
     * @return Builder
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * 按照最新回复时间
     * @param Builder $query
     * @return Builder
     */
    public function scopeRecentReplied(Builder $query): Builder
    {
        return $query->orderBy('updated_at', 'desc');
    }

    /**
     * @param Builder $query
     * @param string $order
     */
    public function scopeWithOrder(Builder $query, string $order): Builder
    {
        switch ($order) {
            case 'recent':
                $query->recent();
                break;
            default:
                $query->recentReplied();
                break;
        }

        return $query->with('user', 'category');
    }

    public function link($params = []): string
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }
}
