@extends('layouts.child')

@section('title', 'Chat')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-[24px] border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <section class="child-card child-soft rounded-[32px] p-6 sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    @if($penPal?->profile?->avatar_type === 'upload' && $penPal?->profile?->avatar)
                        <img src="{{ asset('storage/' . $penPal->profile->avatar) }}" alt="Avatar"
                            class="h-16 w-16 rounded-3xl object-cover">
                    @elseif($penPal?->profile?->avatar_type === 'library' && $penPal?->profile?->avatarLibrary?->image_path)
                        <img src="{{ asset('storage/' . $penPal->profile->avatarLibrary->image_path) }}" alt="Avatar"
                            class="h-16 w-16 rounded-3xl object-cover">
                    @else
                        <div class="flex h-16 w-16 items-center justify-center rounded-3xl child-gradient text-white">
                            <i class="fa-solid fa-user text-xl"></i>
                        </div>
                    @endif

                    <div>
                        <div class="inline-flex rounded-full bg-[#fff7fc] px-3 py-1 text-xs font-semibold text-[#CB148B]">
                            Approved Pen Pal Chat
                        </div>
                        <h1 class="mt-3 text-3xl font-bold text-black">{{ $penPal->name }}</h1>
                        <p class="mt-1 text-sm text-black/55">
                            You can only chat with children who have an approved match with you.
                        </p>
                    </div>
                </div>

                <a href="{{ route('child.letters.create', $penPal) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-semibold text-white">
                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                    Write Letter
                </a>
            </div>
        </section>

        <section class="child-card rounded-[30px] p-6">
            <h2 class="text-2xl font-bold text-black">Conversation</h2>

            <div class="mt-6 space-y-4">
                @forelse($conversation->messages->sortBy('created_at') as $message)
                    <div class="flex {{ $message->sender_user_id === $child->id ? 'justify-end' : 'justify-start' }}">
                        <div
                            class="max-w-xl rounded-[24px] px-5 py-4 {{ $message->sender_user_id === $child->id ? 'bg-[linear-gradient(135deg,#CB148B,#620A88)] text-white' : 'bg-neutral-100 text-black' }}">
                            <p class="text-sm leading-7">{{ $message->message }}</p>
                            <p
                                class="mt-2 text-xs {{ $message->sender_user_id === $child->id ? 'text-white/75' : 'text-black/45' }}">
                                {{ $message->created_at?->format('d M Y, h:i A') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[24px] border border-dashed border-black/10 p-6 text-center text-sm text-black/55">
                        No messages yet. Start with a friendly hello.
                    </div>
                @endforelse
            </div>

            <form action="{{ route('child.messages.send', $penPal) }}" method="POST" class="mt-6">
                @csrf
                <div class="rounded-[28px] border border-black/8 bg-white p-4">
                    <label class="mb-2 block text-sm font-semibold text-black/70">Send a message</label>
                    <textarea name="message" rows="4"
                        class="w-full rounded-2xl border border-black/10 bg-white px-4 py-3 text-sm text-black outline-none focus:border-[#CB148B]"
                        placeholder="Write a kind message...">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="mt-4 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-5 py-3 text-sm font-semibold text-white">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                            Send Message
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection