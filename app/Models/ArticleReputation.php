<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleReputation extends Model
{
    protected $fillable = [
        'user_id',
        'article_id',
        'is_upvote',
    ];
}
