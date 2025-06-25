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
