<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // 要確保這邊的元素應該和 migration 裡的一樣
    protected $fillable = [
        'name',
        'qty',
        'price',
        'description'
    ];
}
