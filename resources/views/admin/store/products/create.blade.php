@extends('layouts.admin')

@section('title', 'Create Product')
@section('page_title', 'Create Product')
@section('page_subtitle', 'Add a new child shop product')

@section('content')
    <div class="rounded-[30px] border border-black/8 bg-white p-6 shadow-sm">
        <form action="{{ route('admin.store.products.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @include('admin.store.products._form', ['product' => new \App\Models\Product()])
            <button type="submit"
                class="rounded-full bg-[linear-gradient(135deg,#620A88,#CB148B)] px-6 py-3 text-sm font-medium text-white">Create
                Product</button>
        </form>
    </div>
@endsection