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
