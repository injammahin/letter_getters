@extends('layouts.admin')

@section('title', 'Edit Product')
@section('page_title', 'Edit Product')
@section('page_subtitle', 'Update child shop product')

@section('content')
    <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
        <form action="{{ route('admin.store.products.update', $product) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PATCH')
            @include('admin.store.products._form', ['product' => $product])

            <div class="flex gap-3">
                <button type="submit"
                    class="rounded-full bg-[linear-gradient(135deg,#620A88,#CB148B)] px-6 py-3 text-sm font-medium text-white">Update
                    Product</button>
                <a href="{{ route('admin.store.products.index') }}"
                    class="rounded-full border border-black/10 px-6 py-3 text-sm font-medium text-neutral-700">Back</a>
            </div>
        </form>
    </div>
@endsection