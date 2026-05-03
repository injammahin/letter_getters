@extends('layouts.admin')

@section('title', 'Manage Habitant Theme')

@section('content')
    @php
        $background = $theme->assets->firstWhere('type', 'background');
        $avatar = $theme->assets->firstWhere('type', 'avatar');
        $food = $theme->assets->firstWhere('type', 'food');
        $toy = $theme->assets->firstWhere('type', 'toy');
        $decorations = $theme->assets->where('type', 'decoration')->values();

        $fieldClass = 'w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-black outline-none transition focus:border-[#620A88] focus:ring-4 focus:ring-[rgba(98,10,136,0.08)]';
        $labelClass = 'mb-2 block text-sm font-bold text-black';
        $cardClass = 'rounded-[28px] border border-black/10 bg-white p-5 shadow-sm';
    @endphp

    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                <p class="font-bold">Please fix the following errors:</p>

                <ul class="mt-2 list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="rounded-[32px] border border-black/10 bg-white p-6 shadow-sm">
            <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
                <div>
                    <div class="inline-flex rounded-full bg-[#fff7fc] px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-[#CB148B]">
                        Habitant Setup
                    </div>

                    <h1 class="mt-4 text-3xl font-black text-black">
                        Manage {{ $theme->name }}
                    </h1>

                    <p class="mt-2 max-w-3xl text-sm leading-7 text-black/55">
                        Upload everything for this theme from one simple page. Start with background, then avatar bundle,
                        then food, toy, and decorations.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.habitants.index') }}"
                        class="rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-bold text-black/60 hover:border-[#620A88] hover:text-[#620A88]">
                        Back to Themes
                    </a>
                </div>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-5">
                <div class="rounded-3xl bg-[#fff7fc] p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#CB148B]">Background</p>
                    <p class="mt-2 text-2xl font-black text-black">{{ $background ? 'Done' : 'Missing' }}</p>
                </div>

                <div class="rounded-3xl bg-[#f7efff] p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#620A88]">Avatar</p>
                    <p class="mt-2 text-2xl font-black text-black">{{ $avatar ? 'Done' : 'Missing' }}</p>
                </div>

                <div class="rounded-3xl bg-amber-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-amber-700">Food</p>
                    <p class="mt-2 text-2xl font-black text-black">{{ $food ? 'Done' : 'Missing' }}</p>
                </div>

                <div class="rounded-3xl bg-green-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-green-700">Toy</p>
                    <p class="mt-2 text-2xl font-black text-black">{{ $toy ? 'Done' : 'Missing' }}</p>
                </div>

                <div class="rounded-3xl bg-blue-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-blue-700">Decorations</p>
                    <p class="mt-2 text-2xl font-black text-black">{{ $decorations->count() }}</p>
                </div>
            </div>
        </section>

        <section class="{{ $cardClass }}">
            <div class="mb-5 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-black text-black">Theme Information</h2>
                    <p class="mt-1 text-sm text-black/55">Basic name, description, thumbnail, and active status.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.habitants.update', $theme) }}" enctype="multipart/form-data" class="grid gap-5 lg:grid-cols-2">
                @csrf
                @method('PUT')

                <div>
                    <label class="{{ $labelClass }}">Theme Name</label>
                    <input type="text" name="name" value="{{ old('name', $theme->name) }}" class="{{ $fieldClass }}" required>
                </div>

                <div>
                    <label class="{{ $labelClass }}">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $theme->sort_order) }}" class="{{ $fieldClass }}">
                </div>

                <div class="lg:col-span-2">
                    <label class="{{ $labelClass }}">Description</label>
                    <textarea name="description" rows="3" class="{{ $fieldClass }}">{{ old('description', $theme->description) }}</textarea>
                </div>

                <div>
                    <label class="{{ $labelClass }}">Thumbnail Image</label>
                    <input type="file" name="thumbnail_image" accept="image/*" class="{{ $fieldClass }}">

                    @if($theme->thumbnail_url)
                        <img src="{{ $theme->thumbnail_url }}" alt="{{ $theme->name }}" class="mt-3 h-24 w-24 rounded-2xl object-cover">
                    @endif
                </div>

                <div class="flex items-end">
                    <label class="flex w-full items-center gap-3 rounded-2xl border border-black/10 px-4 py-3 text-sm font-semibold text-black/70">
                        <input type="checkbox" name="is_active" value="1" {{ $theme->is_active ? 'checked' : '' }}>
                        Active Theme
                    </label>
                </div>

                <div class="lg:col-span-2 flex justify-end">
                    <button type="submit" class="rounded-full bg-[#620A88] px-5 py-3 text-sm font-bold text-white">
                        Save Theme Info
                    </button>
                </div>
            </form>
        </section>

        <section class="{{ $cardClass }}">
            <div class="mb-5 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <div>
                    <h2 class="text-xl font-black text-black">1. Habitat Background</h2>
                    <p class="mt-1 text-sm text-black/55">
                        Upload the main background image. Child must buy this first.
                    </p>
                </div>

                @if($background)
                    <span class="rounded-full bg-green-50 px-4 py-2 text-xs font-black text-green-700">
                        Uploaded
                    </span>
                @else
                    <span class="rounded-full bg-red-50 px-4 py-2 text-xs font-black text-red-700">
                        Missing
                    </span>
                @endif
            </div>

            @include('admin.habitants.partials.asset-form', [
                'theme' => $theme,
                'asset' => $background,
                'type' => 'background',
                'title' => 'Background',
                'defaultName' => 'Lion Habitat Background',
                'defaultPrice' => 100,
                'requiresAvatarStates' => false,
                'fieldClass' => $fieldClass,
                'labelClass' => $labelClass,
            ])
        </section>

        <section class="{{ $cardClass }}">
            <div class="mb-5 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <div>
                    <h2 class="text-xl font-black text-black">2. Avatar Bundle</h2>
                    <p class="mt-1 text-sm text-black/55">
                        Upload one avatar bundle. It includes idle, walking, eating, and sad images.
                    </p>
                </div>

                @if($avatar)
                    <span class="rounded-full bg-green-50 px-4 py-2 text-xs font-black text-green-700">
                        Uploaded
                    </span>
                @else
                    <span class="rounded-full bg-red-50 px-4 py-2 text-xs font-black text-red-700">
                        Missing
                    </span>
                @endif
            </div>

            @include('admin.habitants.partials.asset-form', [
                'theme' => $theme,
                'asset' => $avatar,
                'type' => 'avatar',
                'title' => 'Avatar Bundle',
                'defaultName' => 'Lion Avatar',
                'defaultPrice' => 150,
                'requiresAvatarStates' => true,
                'fieldClass' => $fieldClass,
                'labelClass' => $labelClass,
            ])
        </section>

        <section class="{{ $cardClass }}">
            <div class="mb-5 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <div>
                    <h2 class="text-xl font-black text-black">3. Food Item</h2>
                    <p class="mt-1 text-sm text-black/55">
                        Upload the food item, for example meat snack for lion.
                    </p>
                </div>

                @if($food)
                    <span class="rounded-full bg-green-50 px-4 py-2 text-xs font-black text-green-700">
                        Uploaded
                    </span>
                @else
                    <span class="rounded-full bg-red-50 px-4 py-2 text-xs font-black text-red-700">
                        Missing
                    </span>
                @endif
            </div>

            @include('admin.habitants.partials.asset-form', [
                'theme' => $theme,
                'asset' => $food,
                'type' => 'food',
                'title' => 'Food Item',
                'defaultName' => 'Meat Snack',
                'defaultPrice' => 30,
                'requiresAvatarStates' => false,
                'fieldClass' => $fieldClass,
                'labelClass' => $labelClass,
            ])
        </section>

        <section class="{{ $cardClass }}">
            <div class="mb-5 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <div>
                    <h2 class="text-xl font-black text-black">4. Toy Item</h2>
                    <p class="mt-1 text-sm text-black/55">
                        Upload a toy item, for example playing ball for lion.
                    </p>
                </div>

                @if($toy)
                    <span class="rounded-full bg-green-50 px-4 py-2 text-xs font-black text-green-700">
                        Uploaded
                    </span>
                @else
                    <span class="rounded-full bg-red-50 px-4 py-2 text-xs font-black text-red-700">
                        Missing
                    </span>
                @endif
            </div>

            @include('admin.habitants.partials.asset-form', [
                'theme' => $theme,
                'asset' => $toy,
                'type' => 'toy',
                'title' => 'Toy Item',
                'defaultName' => 'Rolling Ball',
                'defaultPrice' => 50,
                'requiresAvatarStates' => false,
                'fieldClass' => $fieldClass,
                'labelClass' => $labelClass,
            ])
        </section>

        <section class="{{ $cardClass }}">
            <div class="mb-5 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <div>
                    <h2 class="text-xl font-black text-black">5. Decoration Items</h2>
                    <p class="mt-1 text-sm text-black/55">
                        Add multiple decorations like den, tree, flower, crown, rug, or magic stones.
                    </p>
                </div>

                <span class="rounded-full bg-blue-50 px-4 py-2 text-xs font-black text-blue-700">
                    {{ $decorations->count() }} Items
                </span>
            </div>

            <div class="rounded-[24px] border border-dashed border-black/10 bg-gray-50 p-5">
                <h3 class="text-base font-black text-black">Add New Decoration</h3>

                <div class="mt-4">
                    @include('admin.habitants.partials.asset-form', [
                        'theme' => $theme,
                        'asset' => null,
                        'type' => 'decoration',
                        'title' => 'Decoration',
                        'defaultName' => 'Rock Den',
                        'defaultPrice' => 60,
                        'requiresAvatarStates' => false,
                        'fieldClass' => $fieldClass,
                        'labelClass' => $labelClass,
                    ])
                </div>
            </div>

            <div class="mt-6 grid gap-5 lg:grid-cols-2">
                @forelse($decorations as $decoration)
                    <div class="rounded-[24px] border border-black/10 bg-white p-5">
                        <div class="mb-4 flex items-center gap-4">
                            @if($decoration->image_url)
                                <img src="{{ $decoration->image_url }}" alt="{{ $decoration->name }}" class="h-20 w-20 rounded-2xl object-contain bg-gray-50">
                            @else
                                <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-gray-50 text-black/30">
                                    <i class="fa-solid fa-image text-xl"></i>
                                </div>
                            @endif

                            <div>
                                <h3 class="text-lg font-black text-black">{{ $decoration->name }}</h3>
                                <p class="mt-1 text-sm text-black/50">{{ $decoration->price_coins }} coins</p>
                            </div>
                        </div>

                        @include('admin.habitants.partials.asset-form', [
                            'theme' => $theme,
                            'asset' => $decoration,
                            'type' => 'decoration',
                            'title' => 'Decoration',
                            'defaultName' => $decoration->name,
                            'defaultPrice' => $decoration->price_coins,
                            'requiresAvatarStates' => false,
                            'fieldClass' => $fieldClass,
                            'labelClass' => $labelClass,
                        ])

                        <form method="POST"
                            action="{{ route('admin.habitants.assets.destroy', [$theme, $decoration]) }}"
                            class="mt-4"
                            onsubmit="return confirm('Delete this decoration?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="w-full rounded-full border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-700 hover:bg-red-100">
                                Delete Decoration
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-black/10 p-6 text-sm text-black/50">
                        No decoration uploaded yet.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection