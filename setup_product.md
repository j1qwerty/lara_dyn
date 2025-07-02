## add new products table to db 
- create products migration and controller  ` php artisan make:model Product  `

### add products to db
- edit database/migration/ products 
```php

    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // store image path
            $table->timestamps();
        });
    }

```
- migrate  ` php artisan migrate  `

### ADD routes in routes/web.php 
```php 
    Route::get('/dashboard', [ProductController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);
```

### controller
- ediT App/Http/Controllers/ProductController.php `
- store images in public/uploads/products/
```
 // Handle Image 1 upload
        if ($request->hasFile('image_1')) {
            // Delete old image file if it exists
            if (!empty($data->image_1) && file_exists(public_path($data->image_1))) {
                unlink(public_path($data->image_1));
            }
            $file = $request->file('image_1');
            $filename = time() . '_image1_' . $file->getClientOriginalName();
            $file->move(public_path('upload/a4page2section1'), $filename);
            $data->image_1 = 'upload/a4page2section1/' . $filename;
        }
```

```php 

        <?php

        namespace App\Http\Controllers;

        use App\Models\Products;
        use App\Http\Controllers\Controller;
        use Illuminate\Http\Request;
        use Illuminate\Support\Str;


        class ProductsController extends Controller
        {
            /**
             * Display a listing of the resource.
             */
            public function index()
            {
                $products = Products::latest()->paginate(10);
                return view('admin.products.index', compact('products'));
            }

            /**
             * Show the form for creating a new resource.
             */
            public function create()
            {
                return view('admin.products.create');
            }

            /**
             * Store a newly created resource in storage.
             */
            public function store(Request $request)
            {
                
                $request->validate([
                            'name' => 'required|string',
                            'price' => 'required|numeric',
                            'description' => 'nullable|string',
                            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                        ]);

                        $path = null;
                        if ($request->hasFile('image')) {
                            $image = $request->file('image');
                            $filename = now()->format('YmdHis') . '_' . Str::random(5) . '.' . $image->getClientOriginalExtension();
                            // $path = $image->storeAs('uploads', $filename, 'public');
                            $path = $image->move(('public/uploads'), $filename);
                        }

                        Products::create([
                            'name' => $request->name,
                            'price' => $request->price,
                            'description' => $request->description,
                            'image' => $path,
                        ]);

                        return redirect()->route('products.index')->with('success', 'Product added');
            }

            /**
             * Display the specified resource.
             */
            public function show(Products $products)
            {
                //
            }

            /**
             * Show the form for editing the specified resource.
             */
            public function edit(Products $product)
            {
                return view('admin.products.edit', compact('product'));
            }

            /**
             * Update the specified resource in storage.
             */
            public function update(Request $request, Products $product)
            {
                $request->validate([
                            'name' => 'required|string',
                            'price' => 'required|numeric',
                            'description' => 'nullable|string',
                            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                        ]);

                        $path = $product->image;
                        if ($request->hasFile('image')) {
                            $image = $request->file('image');
                            $filename = now()->format('YmdHis') . '_' . Str::random(5) . '.' . $image->getClientOriginalExtension();
                            $path = $image->move(('public/uploads'), $filename);
                        }

                        $product->update([
                            'name' => $request->name,
                            'price' => $request->price,
                            'description' => $request->description,
                            'image' => $path,
                        ]);

                        return redirect()->route('products.index')->with('success', 'Product updated');
            }

            /**
             * Remove the specified resource from storage.
             */
            public function destroy(Products $product)
            {
                $product->delete();
                return redirect()->route('products.index')->with('success', 'Product deleted');
            }
        }


```

### views  resources/views/admin/products/
- index.blade.php
```php

@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-6">All Products</h2>
    <a href="{{ route('products.create') }}" class="mb-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Product</a>

    @foreach($products as $product)
    <div class="bg-white shadow-md rounded p-4 mb-4">
        <h3 class="text-lg font-bold">{{ $product->name }} <span class="text-sm text-gray-600">${{ $product->price }}</span></h3>
        
        @if($product->image)
            <img src="{{ asset($product->image) }}" class="w-32 my-2 rounded">
        @endif

        <p class="text-gray-700 mb-4">{{ $product->description }}</p>

        <div class="flex gap-2">
            <a href="{{ route('products.edit', $product) }}" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>

            <form action="{{ route('products.destroy', $product) }}" method="POST">
                @csrf @method('DELETE')
                <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700" onclick="return confirm('Delete this product?')">Delete</button>
            </form>
        </div>
    </div>
    @endforeach

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>
@endsection

```

- create.blade.php
```php
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-6">Add Product</h2>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @include('admin.products.form')
        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Save</button>
    </form>
</div>
@endsection
```

- edit.blade.php
```php 
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-6">Edit Product</h2>
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')
        @include('admin.products.form')
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
    </form>
</div>
@endsection
```

- form.blade.php
```php

<div>
    <label class="block font-medium mb-1">Name</label>
    <input type="text" name="name" class="w-full border-gray-300 rounded p-2" value="{{ old('name', $product->name ?? '') }}">
</div>

<div>
    <label class="block font-medium mb-1">Price</label>
    <input type="number" step="0.01" name="price" class="w-full border-gray-300 rounded p-2" value="{{ old('price', $product->price ?? '') }}">
</div>

<div>
    <label class="block font-medium mb-1">Description</label>
    <textarea name="description" class="w-full border-gray-300 rounded p-2">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div>
    <label class="block font-medium mb-1">Image</label>
    <input type="file" name="image" class="block w-full text-sm text-gray-500">
    @if(!empty($product->image))
        <img src="{{ asset('storage/' . $product->image) }}" class="w-32 mt-2 rounded">
    @endif
</div>
```

### nav links
```php
 <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products')">
                        Products
                    </x-nav-link>
                    <x-nav-link :href="route('products.create')" :active="request()->routeIs('products.create')">
                        Add Products
                    </x-nav-link>

                </div>
```
