<?php
namespace app\models;

class CommentModel extends Model
{
    protected $fillable = [
        'user_id',
        'news_id',
        'content',
        'created_at'
    ];
}
