<?php
namespace app\models;

class CategoryModel extends Model
{
    protected $table = 'categories';
    
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'photo',
    ];
}