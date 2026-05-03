@extends('layouts.admin')

@section('title', 'Habitant Themes')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col justify-between gap-4 rounded-3xl border border-black/10 bg-white p-6 shadow-sm md:flex-row md:items-center">
            <div>
                <h1 class="text-2xl font-black text-black">Habitant Themes</h1>
                <p class="mt-2 text-sm text-black/55">
                    Create themes like Lion Theme, Unicorn Theme, Ocean Theme, and upload purchasable items.
                </p>
            </div>

            <a href="{{ route('admin.habitants.create') }}"
               class="inline-flex items-center justify-center rounded-full bg-[#620A88] px-5 py-3 text-sm font-bold text-white">
                Add New Theme
            </a>
        </div>

        <div class="overflow-hidden rounded-3xl border border-black/10 bg-white shadow-sm">
            <table class="w-full min-w-[800px] text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wider text-black/50">
                    <tr>
                        <th class="px-5 py-4">Theme</th>
                        <th class="px-5 py-4">Assets</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Sort</th>
                        <th class="px-5 py-4 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-black/5">
                    @forelse($themes as $theme)
                        <tr>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if($theme->thumbnail_url)
                                        <img src="{{ $theme->thumbnail_url }}" alt="{{ $theme->name }}"
                                             class="h-14 w-14 rounded-2xl object-cover">
                                    @else
                                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-50 text-purple-700">
                                            <i class="fa-solid fa-palette"></i>
                                        </div>
                                    @endif

                                    <div>
                                        <p class="font-bold text-black">{{ $theme->name }}</p>
                                        <p class="text-xs text-black/45">{{ $theme->slug }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4 font-semibold text-black/70">
                                {{ $theme->assets_count }}
                            </td>

                            <td class="px-5 py-4">
                                @if($theme->is_active)
                                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-bold text-green-700">
                                        Active
                                    </span>
                                @else
                                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-black/60">
                                {{ $theme->sort_order }}
                            </td>

                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('admin.habitants.edit', $theme) }}"
                                   class="inline-flex rounded-full border border-black/10 px-4 py-2 text-xs font-bold text-black/70 hover:border-[#620A88] hover:text-[#620A88]">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-black/50">
                                No themes added yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection