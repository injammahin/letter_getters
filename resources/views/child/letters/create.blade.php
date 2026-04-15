@extends('layouts.child')

@section('title', 'Write Letter')

@section('content')
    <div class="space-y-6">
        <section class="child-card child-soft rounded-[32px] p-6 sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div
                        class="inline-flex rounded-full border border-[rgba(203,20,139,0.16)] bg-[rgba(203,20,139,0.08)] px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-[#CB148B]">
                        Write Letter
                    </div>
                    <h1 class="mt-4 text-3xl font-bold text-black sm:text-4xl">
                        Write to {{ $penPal->name }}
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-black/60">
                        This letter is allowed because this pen pal match is approved.
                    </p>
                </div>

                <a href="{{ route('child.messages.chat', $penPal) }}"
                    class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-semibold text-black/70">
                    <i class="fa-solid fa-message text-xs"></i>
                    Open Chat
                </a>
            </div>
        </section>

        <section class="child-card rounded-[30px] p-6">
            <form action="{{ route('child.letters.store', $penPal) }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-semibold text-black/70">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject') }}"
                        class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-black outline-none focus:border-[#CB148B]"
                        placeholder="Write a subject">
                    @error('subject')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-black/70">Letter</label>
                    <textarea name="body" rows="10"
                        class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-black outline-none focus:border-[#620A88]"
                        placeholder="Write your letter here...">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3 text-sm font-semibold text-white">
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                        Submit Letter
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection