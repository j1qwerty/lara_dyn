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