@extends('layouts.guest')

@section('content')
<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-[32px] border border-black/8 bg-white p-8 text-center shadow-[0_24px_70px_rgba(17,17,17,0.08)]">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white">
                <i class="fa-solid fa-envelope-circle-check text-2xl"></i>
            </div>

            <h1 class="mt-6 text-3xl font-black text-black">Child Registration Submitted</h1>

            <p class="mx-auto mt-4 max-w-2xl text-base leading-8 text-black/65">
                The child account for <strong>{{ session('child_name') }}</strong> is now waiting for parent approval.
                We sent an approval email to <strong>{{ session('parent_email') }}</strong>.
            </p>

            <p class="mx-auto mt-3 max-w-2xl text-base leading-8 text-black/65">
                The child account will remain pending until the parent confirms registration. :contentReference[oaicite:5]{index=5}
            </p>

            <div class="mt-8">
                <a href="{{ route('home') }}" class="inline-flex rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3 text-sm font-bold text-white">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</section>
@endsection