<?php
namespace app\models;

class UserModel extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'photo',
        'level',
        'created_at'
    ];

    protected $hidden = ['password'];
}