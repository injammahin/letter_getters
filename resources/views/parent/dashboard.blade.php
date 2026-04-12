@extends('layouts.parent')

@section('title', 'Parent Dashboard')
@section('page_title', 'Parent Dashboard')
@section('page_subtitle', 'View child activity, approvals, and letter updates')

@section('content')
    <div class="space-y-6">
        <section class="parent-card parent-soft-gradient rounded-[32px] p-6 sm:p-8">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <div class="inline-flex rounded-full border border-[rgba(203,20,139,0.16)] bg-[rgba(203,20,139,0.08)] px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-[#CB148B]">
                        Parent Overview
                    </div>

                    <h2 class="mt-5 text-3xl font-black text-black sm:text-4xl">
                        Welcome, {{ auth()->user()->name }}
                    </h2>

                    <p class="mt-4 max-w-2xl text-base leading-8 text-black/65">
                        This is a simple parent dashboard for now. Here you will manage child approvals,
                        review letter previews, and monitor activity.
                    </p>
                </div>

                <div class="parent-gradient flex h-20 w-20 items-center justify-center rounded-3xl text-white shadow-[0_18px_34px_rgba(98,10,136,0.18)]">
                    <i class="fa-solid fa-user-shield text-3xl"></i>
                </div>
            </div>
        </section>

        <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <div class="parent-card rounded-[28px] p-5">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#CB148B] text-white">
                    <i class="fa-solid fa-child-reaching"></i>
                </div>
                <h3 class="text-lg font-black text-black">My Children</h3>
                <p class="mt-2 text-3xl font-black text-black">1</p>
                <p class="mt-2 text-sm text-black/55">Linked child accounts</p>
            </div>

            <div class="parent-card rounded-[28px] p-5">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#620A88] text-white">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <h3 class="text-lg font-black text-black">Pending Approvals</h3>
                <p class="mt-2 text-3xl font-black text-black">0</p>
                <p class="mt-2 text-sm text-black/55">Waiting for your review</p>
            </div>

            <div class="parent-card rounded-[28px] p-5">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-black text-white">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
                <h3 class="text-lg font-black text-black">Letter Previews</h3>
                <p class="mt-2 text-3xl font-black text-black">0</p>
                <p class="mt-2 text-sm text-black/55">Letters ready for viewing</p>
            </div>

            <div class="parent-card rounded-[28px] p-5">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <h3 class="text-lg font-black text-black">Notifications</h3>
                <p class="mt-2 text-3xl font-black text-black">2</p>
                <p class="mt-2 text-sm text-black/55">Recent updates and alerts</p>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <div class="parent-card rounded-[28px] p-6">
                <h3 class="text-2xl font-black text-black">Quick Actions</h3>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <a href="#" class="rounded-2xl border border-black/8 p-4 transition hover:border-[#CB148B] hover:bg-[#fff7fc]">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#CB148B] text-white">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-black">Approve Requests</p>
                                <p class="text-xs text-black/50">Child approvals</p>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="rounded-2xl border border-black/8 p-4 transition hover:border-[#620A88] hover:bg-[#f7efff]">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#620A88] text-white">
                                <i class="fa-solid fa-envelope-open-text"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-black">Review Letters</p>
                                <p class="text-xs text-black/50">Preview letters</p>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="rounded-2xl border border-black/8 p-4 transition hover:border-black/20 hover:bg-slate-50">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-black text-white">
                                <i class="fa-solid fa-folder-open"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-black">Letter Archive</p>
                                <p class="text-xs text-black/50">Saved letters</p>
                            </div>
                        </div>
                    </a>

                    <a href="#" class="rounded-2xl border border-black/8 p-4 transition hover:border-[#CB148B] hover:bg-[#fff7fc]">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-black">Privacy Settings</p>
                                <p class="text-xs text-black/50">Manage visibility</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="parent-card rounded-[28px] p-6">
                <h3 class="text-2xl font-black text-black">Account Summary</h3>

                <div class="mt-6 space-y-4">
                    <div class="rounded-2xl bg-[#fff7fc] p-4">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#CB148B]">Role</p>
                        <p class="mt-2 text-base font-black text-black">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>

                    <div class="rounded-2xl bg-[#f7efff] p-4">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#620A88]">Email</p>
                        <p class="mt-2 text-base font-black text-black">{{ auth()->user()->email }}</p>
                    </div>

                    <div class="rounded-2xl bg-black p-4 text-white">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-white/70">Status</p>
                        <p class="mt-2 text-base font-black">{{ ucfirst(auth()->user()->account_status ?? 'active') }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection