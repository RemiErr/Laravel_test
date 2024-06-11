<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

<<<<<<< HEAD
    // 要確保這邊的元素應該和 migration 裡的一樣
    protected $fillable = [
        'name',
        'qty',
        'price',
        'description'
    ];
=======
    protected $table = 'products';
    protected $primaryKey = 'product_id';
    public $timestamps = true;

    protected $fillable = [
        'product_name',
        'product_discription',
        'product_price',
        'product_discount'
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items', 'product_id', 'order_id');
    }
>>>>>>> dev-240517
}
