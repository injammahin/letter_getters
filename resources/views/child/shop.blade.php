@extends('layouts.child')

@section('title', 'Shop')

@section('content')
    <div class="space-y-6">
        <section class="child-card child-soft rounded-[32px] p-6 sm:p-8">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div
                        class="inline-flex rounded-full border border-[rgba(203,20,139,0.16)] bg-[rgba(203,20,139,0.08)] px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-[#CB148B]">
                        Child Shop
                    </div>
                    <h1 class="mt-4 text-3xl font-bold text-black sm:text-4xl">
                        Spend coins on fun rewards
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-black/60">
                        This is the child-friendly reward shop where children can use earned coins for avatar items,
                        printables, and special themed bundles.
                    </p>
                </div>

                <div class="rounded-[28px] border border-black/8 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-black/45">Coin Balance</p>
                    <div class="mt-3 flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-black text-white">
                            <i class="fa-solid fa-coins"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-black">0</p>
                            <p class="text-sm text-black/50">Available coins</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="child-card rounded-[28px] p-5">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#CB148B] text-white">
                    <i class="fa-solid fa-user-astronaut"></i>
                </div>
                <h3 class="text-lg font-bold text-black">Avatar Items</h3>
                <p class="mt-2 text-sm leading-7 text-black/55">
                    Unlock cute avatar styles, seasonal packs, and child-safe profile decorations.
                </p>
            </div>

            <div class="child-card rounded-[28px] p-5">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#620A88] text-white">
                    <i class="fa-solid fa-star"></i>
                </div>
                <h3 class="text-lg font-bold text-black">Printables</h3>
                <p class="mt-2 text-sm leading-7 text-black/55">
                    Get themed stationery, letter paper, and printable fun sets.
                </p>
            </div>

            <div class="child-card rounded-[28px] p-5">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-black text-white">
                    <i class="fa-solid fa-gift"></i>
                </div>
                <h3 class="text-lg font-bold text-black">Bundles</h3>
                <p class="mt-2 text-sm leading-7 text-black/55">
                    Explore grouped rewards that combine fun items in one unlock.
                </p>
            </div>

            <div class="child-card rounded-[28px] p-5">
                <div
                    class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <h3 class="text-lg font-bold text-black">Coming Soon</h3>
                <p class="mt-2 text-sm leading-7 text-black/55">
                    More shop sections can be added later as the coin reward system grows.
                </p>
            </div>
        </section>

        <section class="child-card rounded-[30px] p-6">
            <h2 class="text-2xl font-bold text-black">Featured reward items</h2>
            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <div class="rounded-[24px] border border-black/8 p-5 hover:border-[#CB148B] hover:bg-[#fff7fc]">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#fff0f9] text-[#CB148B]">
                        <i class="fa-solid fa-cat text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-black">Avatar Pack</h3>
                    <p class="mt-2 text-sm text-black/55">Unlock fun avatar designs and profile style items.</p>
                    <div class="mt-4 inline-flex rounded-full bg-black px-4 py-2 text-sm font-semibold text-white">
                        50 Coins
                    </div>
                </div>

                <div class="rounded-[24px] border border-black/8 p-5 hover:border-[#620A88] hover:bg-[#f7efff]">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#f5efff] text-[#620A88]">
                        <i class="fa-solid fa-file-lines text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-black">Printable Set</h3>
                    <p class="mt-2 text-sm text-black/55">Get letter templates, paper sets, and fun printable themes.</p>
                    <div class="mt-4 inline-flex rounded-full bg-[#620A88] px-4 py-2 text-sm font-semibold text-white">
                        30 Coins
                    </div>
                </div>

                <div class="rounded-[24px] border border-black/8 p-5 hover:border-black/15 hover:bg-slate-50">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-black text-white">
                        <i class="fa-solid fa-wand-magic-sparkles text-xl"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-black">Mystery Bundle</h3>
                    <p class="mt-2 text-sm text-black/55">A themed child-safe bundle with surprise digital rewards.</p>
                    <div class="mt-4 inline-flex rounded-full bg-[#CB148B] px-4 py-2 text-sm font-semibold text-white">
                        80 Coins
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection