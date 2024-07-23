<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    // 使用到的特性
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = [
        'user_name',
        'user_address',
        'user_phone',
        'user_email',
        'user_password'
    ];

    protected $hidden = [
        'user_password',
    ];

    // 映射請求體屬性名稱
    // setter
    public function setNameAttribute($value)
    {
        $this->attributes['user_name'] = $value;
    }
    public function setAddressAttribute($value)
    {
        $this->attributes['user_address'] = $value;
    }
    public function setPhoneAttribute($value)
    {
        $this->attributes['user_phone'] = $value;
    }
    public function setEmailAttribute($value)
    {
        $this->attributes['user_email'] = $value;
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['user_password'] = $value;
    }

    // getter
    public function getNameAttribute()
    {
        return $this->attributes['user_name'];
    }
    public function getAddressAttribute()
    {
        return $this->attributes['user_address'];
    }
    public function getPhoneAttribute()
    {
        return $this->attributes['user_phone'];
    }
    public function getEmailAttribute()
    {
        return $this->attributes['user_email'];
    }
    public function getPasswordAttribute()
    {
        return $this->attributes['user_password'];
    }

    // 有多個 -> orders
    public function orders()
    {
        // Order 的 user_id = this.user_id
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }


    // tymon/jwt-auth 介面實作
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
}
