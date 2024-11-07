<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'text',
        'author_id',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reputation() {
        return $this->hasMany(ArticleReputation::class);
    }
}
