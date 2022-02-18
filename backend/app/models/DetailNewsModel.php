<?php
namespace app\models;

class DetailNewsModel extends Model
{
    protected $table = 'detail_news';

    protected $fillable = [
        'news_id',
        'content'
    ];
}
