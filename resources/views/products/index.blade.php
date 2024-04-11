<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Product</h1>
    <div>
        @if(session()->has('success'))
        <div>
            {{-- 從 Controller 傳來的 (with) --}}
            {{session('success')}}
        </div>
        @endif
    </div>
    <div>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Description</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            {{-- $products 是從 Controller 傳來的 (string) --}}
            @foreach($products as $product)
            <tr>
                <td>{{$product->id}}</td>
                <td>{{$product->name}}</td>
                <td>{{$product->qty}}</td>
                <td>{{$product->price}}</td>
                <td>{{$product->description}}</td>
                <td>
                    {{-- 透過 route method 指定 name 引導到對應的 --}}
                    {{-- 並將上方 $product 作為 'product' 傳入 Controller 下的 method --}}
                    {{-- <a href="{{route('product.edit', ['product' => $product])}}">Edit</a> --}}
                    <form method="post" action="{{route('product.edit', ['product' => $product])}}">
                        @csrf
                        @method('get')
                        <input type="submit" value="Edit">
                    </form>
                </td>
                <td>
                    {{-- 要刪除資料，必須透過 form --}}
                    <form method="post" action="{{route('product.destroy', ['product' => $product])}}">
                        @csrf
                        @method('delete')
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
        <div>
            <a href="/product/create">Create</a>
        </div>
    </div>
</body>
</html>