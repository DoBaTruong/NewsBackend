<?php
namespace app\models;

class PersonalAccessToken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'secret',
        'expired_at',
        'last_used_at'
    ];
}