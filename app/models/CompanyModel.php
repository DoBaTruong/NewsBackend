<?php
namespace app\models;

class CompanyModel extends Model
{
    protected $table = 'companies';
    
    protected $fillable = [
        'name',
        'slug',
        'descript',
        'logo',
        'contact',
        'address'
    ];
}