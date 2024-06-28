<?php

namespace App\Models;

// 引用 Passport 套件
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // 使用到的特性
    use HasFactory, HasApiTokens;

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
}
