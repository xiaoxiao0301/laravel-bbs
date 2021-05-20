<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'link'
    ];

    protected $casts = [
        'created_at' => "datetime: Y-m-d H:i:s",
        'updated_at' => "datetime: Y-m-d H:i:s",
    ];

    public $cacheKey = 'bbs_links';
    protected $cacheExpire = 1440;

    public function getAllCached()
    {
        return \Cache::remember($this->cacheKey, $this->cacheExpire, function () {
            return $this->all();
        });
    }
}
