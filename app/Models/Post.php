<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Post extends Model
{
    protected $fillable = [
        'title',
        'body',
        'slug',
        'is_published',
        'published_at',
        'image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'author',
        'source',
        'category',
        'tags',
        'status',
        'visibility',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
