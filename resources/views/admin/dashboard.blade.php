@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Super Admin Dashboard')
@section('page_subtitle', 'Overview of users, approvals, letters, subscriptions, and rewards')

@section('content')
@php
    $stats = [
        ['title' => 'Total Users', 'value' => '12,480', 'change' => '+8.2%', 'icon' => 'fa-users', 'icon_bg' => 'bg-[#fff0f9]', 'icon_text' => 'text-[#CB148B]'],
        ['title' => 'Pending Parent Approvals', 'value' => '186', 'change' => '+14 today', 'icon' => 'fa-user-check', 'icon_bg' => 'bg-[#f5efff]', 'icon_text' => 'text-[#620A88]'],
        ['title' => 'Active Matches', 'value' => '3,942', 'change' => '+91 this week', 'icon' => 'fa-user-group', 'icon_bg' => 'bg-[#f4f4f5]', 'icon_text' => 'text-neutral-800'],
        ['title' => 'Letters In Process', 'value' => '428', 'change' => '67 urgent', 'icon' => 'fa-envelope-open-text', 'icon_bg' => 'bg-[#fff4f7]', 'icon_text' => 'text-[#CB148B]'],
        ['title' => 'Active Subscriptions', 'value' => '1,126', 'change' => '+4.9%', 'icon' => 'fa-box-open', 'icon_bg' => 'bg-[#f6f0ff]', 'icon_text' => 'text-[#620A88]'],
        ['title' => 'Store Orders This Month', 'value' => '742', 'change' => '+11.3%', 'icon' => 'fa-store', 'icon_bg' => 'bg-[#f5f5f5]', 'icon_text' => 'text-neutral-900'],
    ];

    $userSegments = [
        ['label' => 'Child Users', 'value' => 48, 'count' => '5,990'],
        ['label' => 'Parent Users', 'value' => 21, 'count' => '2,621'],
        ['label' => 'Adult Users', 'value' => 24, 'count' => '2,995'],
        ['label' => 'Staff & Moderators', 'value' => 7, 'count' => '874'],
    ];

    $mailPipeline = [
        ['label' => 'Received at Office', 'count' => 122, 'percent' => 78],
        ['label' => 'Opened for Review', 'count' => 95, 'percent' => 61],
        ['label' => 'Uploaded for Preview', 'count' => 74, 'percent' => 47],
        ['label' => 'Approved for Forwarding', 'count' => 58, 'percent' => 38],
        ['label' => 'Packed & Sent', 'count' => 44, 'percent' => 28],
    ];

    $activities = [
        ['title' => '14 child letters entered preview queue', 'meta' => 'Mail Operations • 8 minutes ago', 'color' => 'bg-[#CB148B]'],
        ['title' => '6 parent approvals pending verification', 'meta' => 'User Management • 22 minutes ago', 'color' => 'bg-[#620A88]'],
        ['title' => '3 flagged accounts moved to moderation review', 'meta' => 'Moderation • 39 minutes ago', 'color' => 'bg-black'],
        ['title' => 'Subscription kit batch LG-KIT-APR-07 prepared', 'meta' => 'Subscriptions • 1 hour ago', 'color' => 'bg-fuchsia-500'],
        ['title' => '52 new coin transactions processed successfully', 'meta' => 'Digital Rewards • 1 hour ago', 'color' => 'bg-purple-700'],
    ];

    $priorityQueue = [
        ['task' => 'Release parent preview approvals', 'count' => '37 items', 'status' => 'High'],
        ['task' => 'Review reported users', 'count' => '12 cases', 'status' => 'High'],
        ['task' => 'Process subscription renewals', 'count' => '29 renewals', 'status' => 'Medium'],
        ['task' => 'Update homepage campaign content', 'count' => '4 drafts', 'status' => 'Low'],
    ];
@endphp

<div class="space-y-6">
    <section class="admin-card admin-gradient-soft rounded-[30px] p-6 sm:p-8">
        <div class="grid gap-6 xl:grid-cols-[1.45fr_0.9fr]">
            <div>
                <div class="inline-flex rounded-full border border-[rgba(203,20,139,0.14)] bg-[rgba(203,20,139,0.07)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-[#CB148B]">
                    Platform overview
                </div>

                <h2 class="mt-5 max-w-3xl text-[30px] leading-tight font-semibold text-neutral-900 sm:text-[38px]">
                    Clean overview of users, approvals, letters, subscriptions, and rewards
                </h2>

                <p class="mt-4 max-w-3xl text-sm leading-8 text-neutral-600 sm:text-[15px]">
                    This dashboard brings the main platform flows into one place so operations, safety, and growth can be managed without visual clutter.
                </p>

                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ url('/admin/users') }}"
                       class="inline-flex items-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-medium text-white shadow-[0_18px_32px_rgba(98,10,136,0.16)] transition hover:-translate-y-0.5">
                        <i class="fa-solid fa-user-plus text-sm"></i>
                        <span>Add User</span>
                    </a>

                    <a href="{{ url('/admin/mail/preview-queue') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-medium text-neutral-700 transition hover:border-[#CB148B] hover:text-[#CB148B]">
                        <i class="fa-solid fa-envelope-open-text text-sm"></i>
                        <span>Review Letters</span>
                    </a>

                    <a href="{{ url('/admin/reports/users') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-medium text-neutral-700 transition hover:border-[#620A88] hover:text-[#620A88]">
                        <i class="fa-solid fa-chart-line text-sm"></i>
                        <span>View Reports</span>
                    </a>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-3 xl:grid-cols-1">
                <div class="rounded-[28px] bg-[linear-gradient(135deg,#CB148B,#620A88)] p-5 text-white shadow-[0_18px_34px_rgba(98,10,136,0.18)]">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-white/75">Parent approval rate</p>
                    <div class="mt-4 flex items-center justify-between gap-4">
                        <div class="ring-chart" style="background: conic-gradient(#ffffff 0 82%, rgba(255,255,255,0.18) 82% 100%);">
                            <span>82%</span>
                        </div>
                        <div>
                            <p class="text-2xl font-semibold">2,061</p>
                            <p class="mt-1 text-sm text-white/75">approved this month</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-[28px] border border-black/6 bg-white p-5">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#620A88]">Safety queue</p>
                    <p class="mt-3 text-[28px] font-semibold text-neutral-900">27</p>
                    <p class="mt-1 text-sm text-neutral-500">open moderation items</p>
                    <div class="mt-4 progress-track">
                        <div class="progress-bar" style="width: 44%;"></div>
                    </div>
                </div>

                <div class="rounded-[28px] border border-black/6 bg-white p-5">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#CB148B]">Monthly revenue blend</p>
                    <p class="mt-3 text-[28px] font-semibold text-neutral-900">$18.4K</p>
                    <p class="mt-1 text-sm text-neutral-500">subscription + store + premium items</p>
                    <div class="mt-4 flex gap-2">
                        <div class="h-2 flex-1 rounded-full bg-pink-500"></div>
                        <div class="h-2 w-24 rounded-full bg-violet-600"></div>
                        <div class="h-2 w-16 rounded-full bg-black"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($stats as $stat)
            <div class="admin-card rounded-[26px] p-5 transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_18px_34px_rgba(17,17,17,0.06)]">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">{{ $stat['title'] }}</p>
                        <h3 class="mt-3 text-[30px] leading-none font-semibold text-neutral-900">{{ $stat['value'] }}</h3>
                        <p class="mt-2 text-sm font-medium text-[#620A88]">{{ $stat['change'] }}</p>
                    </div>

                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl {{ $stat['icon_bg'] }} {{ $stat['icon_text'] }}">
                        <i class="fa-solid {{ $stat['icon'] }} text-base"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    <section class="grid gap-6 2xl:grid-cols-[1.08fr_0.92fr]">
        <div class="admin-card rounded-[30px] p-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h3 class="text-[24px] font-semibold text-neutral-900">User ecosystem</h3>
                    <p class="mt-1 text-sm text-neutral-500">Distribution across children, parents, adults, and staff</p>
                </div>

                <div class="rounded-full bg-[rgba(203,20,139,0.08)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-[#CB148B]">
                    Live segments
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-[1fr_0.92fr]">
                <div class="space-y-5">
                    @foreach($userSegments as $segment)
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="font-medium text-neutral-800">{{ $segment['label'] }}</span>
                                <span class="font-semibold text-neutral-900">{{ $segment['count'] }}</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-bar" style="width: {{ $segment['value'] }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                    <div class="rounded-[26px] bg-[#fff7fc] p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#CB148B]">Child pathway</p>
                                <h4 class="mt-2 text-2xl font-semibold text-neutral-900">5,990</h4>
                            </div>
                            <div class="ring-chart" style="background: conic-gradient(#CB148B 0 68%, #f3e7ef 68% 100%);">
                                <span>68%</span>
                            </div>
                        </div>
                        <p class="mt-3 text-sm leading-7 text-neutral-600">Largest user group, linked with parent controls and safe matching.</p>
                    </div>

                    <div class="rounded-[26px] bg-[#f7efff] p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#620A88]">Adult pathway</p>
                                <h4 class="mt-2 text-2xl font-semibold text-neutral-900">2,995</h4>
                            </div>
                            <div class="ring-chart" style="background: conic-gradient(#620A88 0 41%, #ebe3f7 41% 100%);">
                                <span>41%</span>
                            </div>
                        </div>
                        <p class="mt-3 text-sm leading-7 text-neutral-600">Separate matching and communication workflow for adult accounts.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card rounded-[30px] p-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h3 class="text-[24px] font-semibold text-neutral-900">Mail operations pipeline</h3>
                    <p class="mt-1 text-sm text-neutral-500">Current letter flow from intake to forwarding</p>
                </div>

                <div class="rounded-full bg-[rgba(98,10,136,0.08)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-[#620A88]">
                    Workflow
                </div>
            </div>

            <div class="mt-8 space-y-5">
                @foreach($mailPipeline as $step)
                    <div class="rounded-2xl border border-black/6 p-4">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <div class="text-sm font-medium text-neutral-800">{{ $step['label'] }}</div>
                            <div class="text-sm font-semibold text-neutral-900">{{ $step['count'] }}</div>
                        </div>
                        <div class="progress-track">
                            <div class="progress-bar" style="width: {{ $step['percent'] }}%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl bg-[#fff7fc] p-4 text-center">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#CB148B]">Awaiting preview</p>
                    <p class="mt-2 text-2xl font-semibold text-neutral-900">74</p>
                </div>

                <div class="rounded-2xl bg-[#f7efff] p-4 text-center">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#620A88]">Needs parent action</p>
                    <p class="mt-2 text-2xl font-semibold text-neutral-900">31</p>
                </div>

                <div class="rounded-2xl bg-black p-4 text-center text-white">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-white/70">In transit</p>
                    <p class="mt-2 text-2xl font-semibold">112</p>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
        <div class="admin-card rounded-[30px] p-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h3 class="text-[24px] font-semibold text-neutral-900">Recent platform activity</h3>
                    <p class="mt-1 text-sm text-neutral-500">Key events across operations, users, rewards, and moderation</p>
                </div>

                <a href="{{ url('/admin/reports') }}" class="text-sm font-medium text-[#620A88] transition hover:text-[#CB148B]">
                    Full activity log
                </a>
            </div>

            <div class="mt-8 space-y-4">
                @foreach($activities as $activity)
                    <div class="flex gap-4 rounded-2xl border border-black/6 p-4">
                        <div class="mt-1 mini-dot {{ $activity['color'] }}"></div>
                        <div>
                            <h4 class="text-sm font-medium leading-6 text-neutral-900">{{ $activity['title'] }}</h4>
                            <p class="mt-1 text-sm text-neutral-500">{{ $activity['meta'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            <div class="admin-card rounded-[30px] p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-[24px] font-semibold text-neutral-900">Priority queue</h3>
                        <p class="mt-1 text-sm text-neutral-500">Tasks that need action from operations or super admin</p>
                    </div>

                    <span class="rounded-full bg-[rgba(203,20,139,0.08)] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-[#CB148B]">
                        Today
                    </span>
                </div>

                <div class="mt-6 space-y-3">
                    @foreach($priorityQueue as $item)
                        <div class="rounded-2xl border border-black/6 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-medium text-neutral-900">{{ $item['task'] }}</p>
                                    <p class="mt-1 text-sm text-neutral-500">{{ $item['count'] }}</p>
                                </div>

                                <span class="rounded-full {{ $item['status'] === 'High' ? 'bg-red-50 text-red-600' : ($item['status'] === 'Medium' ? 'bg-amber-50 text-amber-600' : 'bg-green-50 text-green-600') }} px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em]">
                                    {{ $item['status'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="admin-card rounded-[30px] p-6">
                <h3 class="text-[24px] font-semibold text-neutral-900">Quick action modules</h3>
                <p class="mt-1 text-sm text-neutral-500">Jump into the core platform areas</p>

                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    <a href="{{ url('/admin/users') }}" class="rounded-2xl border border-black/6 p-4 transition hover:border-pink-200 hover:bg-[#fff7fc]">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#fff0f9] text-[#CB148B]">
                                <i class="fa-solid fa-users text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-900">Users</p>
                                <p class="text-xs text-neutral-500">Profiles and roles</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ url('/admin/matches/pending') }}" class="rounded-2xl border border-black/6 p-4 transition hover:border-violet-200 hover:bg-[#f7efff]">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#f5efff] text-[#620A88]">
                                <i class="fa-solid fa-user-group text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-900">Matches</p>
                                <p class="text-xs text-neutral-500">Suggestions and approvals</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ url('/admin/mail/incoming') }}" class="rounded-2xl border border-black/6 p-4 transition hover:border-black/15 hover:bg-slate-50">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#f5f5f5] text-neutral-900">
                                <i class="fa-solid fa-envelope-open-text text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-900">Mail Queue</p>
                                <p class="text-xs text-neutral-500">Intake and preview flow</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ url('/admin/subscriptions/plans') }}" class="rounded-2xl border border-black/6 p-4 transition hover:border-pink-200 hover:bg-[#fff7fc]">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                            <i class="fa-solid fa-box-open text-sm"></i>
                        </div>
                        <div class="mt-3">
                            <p class="text-sm font-medium text-neutral-900">Subscriptions</p>
                            <p class="text-xs text-neutral-500">Plans and kit flow</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-3">
        <div class="admin-card rounded-[30px] p-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#CB148B]">Digital rewards</p>
                    <h3 class="mt-2 text-[24px] font-semibold text-neutral-900">Coins, avatars, and printables</h3>
                </div>

                <div class="ring-chart" style="background: conic-gradient(#CB148B 0 76%, #f2e5ed 76% 100%);">
                    <span>76%</span>
                </div>
            </div>

            <div class="mt-6 space-y-4">
                <div>
                    <div class="mb-2 flex items-center justify-between text-sm">
                        <span class="font-medium text-neutral-800">Coin rule configuration</span>
                        <span class="font-semibold text-neutral-900">92%</span>
                    </div>
                    <div class="progress-track"><div class="progress-bar" style="width: 92%;"></div></div>
                </div>

                <div>
                    <div class="mb-2 flex items-center justify-between text-sm">
                        <span class="font-medium text-neutral-800">Avatar item coverage</span>
                        <span class="font-semibold text-neutral-900">68%</span>
                    </div>
                    <div class="progress-track"><div class="progress-bar" style="width: 68%;"></div></div>
                </div>

                <div>
                    <div class="mb-2 flex items-center justify-between text-sm">
                        <span class="font-medium text-neutral-800">Printable unlock library</span>
                        <span class="font-semibold text-neutral-900">74%</span>
                    </div>
                    <div class="progress-track"><div class="progress-bar" style="width: 74%;"></div></div>
                </div>
            </div>
        </div>

        <div class="admin-card rounded-[30px] p-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#620A88]">Commerce flow</p>
                    <h3 class="mt-2 text-[24px] font-semibold text-neutral-900">Subscriptions and store</h3>
                </div>

                <div class="ring-chart" style="background: conic-gradient(#620A88 0 64%, #e8e0f4 64% 100%);">
                    <span>64%</span>
                </div>
            </div>

            <div class="mt-6 grid gap-3">
                <div class="rounded-2xl bg-[#f7efff] p-4">
                    <p class="text-sm font-medium text-neutral-900">Active kit fulfillment</p>
                    <p class="mt-1 text-sm text-neutral-500">84 monthly kit shipments in progress</p>
                </div>

                <div class="rounded-2xl bg-[#fff7fc] p-4">
                    <p class="text-sm font-medium text-neutral-900">Physical product orders</p>
                    <p class="mt-1 text-sm text-neutral-500">127 orders awaiting packing or shipping</p>
                </div>

                <div class="rounded-2xl bg-black p-4 text-white">
                    <p class="text-sm font-medium">Renewal watchlist</p>
                    <p class="mt-1 text-sm text-white/75">29 subscriptions expire within 7 days</p>
                </div>
            </div>
        </div>

        <div class="admin-card rounded-[30px] p-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-neutral-500">CMS & governance</p>
                    <h3 class="mt-2 text-[24px] font-semibold text-neutral-900">Content and control center</h3>
                </div>

                <div class="ring-chart" style="background: conic-gradient(#111111 0 58%, #ededed 58% 100%);">
                    <span>58%</span>
                </div>
            </div>

            <div class="mt-6 space-y-3">
                <div class="rounded-2xl border border-black/6 p-4">
                    <p class="text-sm font-medium text-neutral-900">Homepage content</p>
                    <p class="mt-1 text-sm text-neutral-500">Spring campaign block ready for publishing</p>
                </div>

                <div class="rounded-2xl border border-black/6 p-4">
                    <p class="text-sm font-medium text-neutral-900">Safety page update</p>
                    <p class="mt-1 text-sm text-neutral-500">Minor policy content review pending approval</p>
                </div>

                <div class="rounded-2xl border border-black/6 p-4">
                    <p class="text-sm font-medium text-neutral-900">Roles and permissions</p>
                    <p class="mt-1 text-sm text-neutral-500">2 new staff roles drafted for operations team</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection