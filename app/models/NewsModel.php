<?php
namespace app\models;

class NewsModel extends Model
{
    protected $table = 'news';
    
    protected $fillable = [
        'title',
        'slug',
        'abstract',
        'photo',
        'category_id',
        'featured',
        'authors',
        'viewer',
        'comment',
        'published_at'
    ];
}
