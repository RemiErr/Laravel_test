<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $timestamps = true;

    protected $fillable = [
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function products()
    {
        // order_items 表裡 [this.order_id, Product.product_id]
        // pivot 代表中介表模型，預設只提供 Key ，多出來的欄位用 withPivot 設定
        return $this->belongsToMany(Product::class, 'order_items', 'order_id', 'product_id')->withPivot('item_quantity');
    }
}
