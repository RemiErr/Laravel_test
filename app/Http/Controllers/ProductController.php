<?php
/*
    Controller 可以理解成資料的中轉站
    它會負責把從 Web 來的資料送到 Route，再由 Route 轉送至 Method 處理。
    亦可將處理後的資料透過 view method 送至 Web 展示。
*/


namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 用到模組功能 需引用
use  App\Models\Product;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();

        // views dir 底下的 products / index
        return view('products.index', ['products' => $products]);

    }

    public function create(){
        return view('products.create');
    }

    public function store(Request $request){
        // 若要攔截顯示 BUG 可用 validateWithBag
        // todo https://youtu.be/_LA9QsgJ0bw?si=rSPyAPddmzQsiIf8&t=1810
        $validated = $request->validate([
            'name' => 'required',
            'qty' => 'required|numeric',
            'price' => 'required|decimal:0,2',
            'description' => 'nullable',
        ]);
        // dd($request->input());

        $newProduct = Product::create($validated);

        // 重新導向 URL，Route Name: 'product.index'
        return redirect(route('product.index'));
    }

    // 從URL傳來的前方帶 product 參數，所以將其傳入 (它是個模組)
    public function edit(Product $product){
        // dd($product);
        return view('products.edit', ['product' => $product]);
    }

    // 前面是收到的產品資料，後面是發送的請求
    public function update(Product $product, Request $request){
        $data = $request->validate([
            'name' => 'required',
            'qty' => 'required|numeric',
            'price' => 'required|decimal:0,2',
            'description' => 'nullable',
        ]);

        $product->update($data);

        // 將操作成功的訊號送回 index
        return redirect(route('product.index'))->with('success', 'Product Updated Successffully');
    }
    
    public function destroy(Product $product){
        $product->delete();
        return redirect(route('product.index'))->with('success', 'Product Delete Successffully');
    }

}





