@php
    $featuresText = old(
        'features_text',
        is_array($subscriptionPlan->features ?? null)
            ? implode("\n", $subscriptionPlan->features)
            : ''
    );
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf

    @if($method !== 'POST')
        @method($method)
    @endif

    <section class="rounded-[30px] border border-black/10 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-black text-black">Basic Information</h2>

        <div class="mt-5 grid gap-5 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-black text-black">Plan Name</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $subscriptionPlan->name) }}"
                       placeholder="Premium Monthly"
                       class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B]"
                       required>

                @error('name')
                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-black text-black">Slug</label>
                <input type="text"
                       name="slug"
                       value="{{ old('slug', $subscriptionPlan->slug) }}"
                       placeholder="premium-monthly"
                       class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B]">

                <p class="mt-2 text-xs text-black/40">
                    Leave empty to generate automatically.
                </p>

                @error('slug')
                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-black text-black">Badge</label>
                <input type="text"
                       name="badge"
                       value="{{ old('badge', $subscriptionPlan->badge) }}"
                       placeholder="Most Popular"
                       class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B]">

                @error('badge')
                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-black text-black">Sort Order</label>
                <input type="number"
                       name="sort_order"
                       value="{{ old('sort_order', $subscriptionPlan->sort_order ?? 0) }}"
                       class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B]">

                @error('sort_order')
                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-5">
            <label class="mb-2 block text-sm font-black text-black">Short Description</label>
            <input type="text"
                   name="short_description"
                   value="{{ old('short_description', $subscriptionPlan->short_description) }}"
                   placeholder="Best for children who want more habitant items and premium features."
                   class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B]">

            @error('short_description')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-5">
            <label class="mb-2 block text-sm font-black text-black">Full Description</label>
            <textarea name="description"
                      rows="4"
                      placeholder="Explain this subscription plan clearly for parents and users."
                      class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B]">{{ old('description', $subscriptionPlan->description) }}</textarea>

            @error('description')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </section>

    <section class="rounded-[30px] border border-black/10 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-black text-black">Price and Billing</h2>

        <div class="mt-5 grid gap-5 md:grid-cols-4">
            <div>
                <label class="mb-2 block text-sm font-black text-black">Price</label>
                <input type="number"
                       step="0.01"
                       min="0"
                       name="price"
                       value="{{ old('price', $subscriptionPlan->price ?? 0) }}"
                       class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B]"
                       required>

                @error('price')
                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-black text-black">Currency</label>
                <input type="text"
                       name="currency"
                       value="{{ old('currency', $subscriptionPlan->currency ?? 'USD') }}"
                       placeholder="USD"
                       class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm uppercase outline-none focus:border-[#CB148B]"
                       required>

                @error('currency')
                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-black text-black">Billing Type</label>
                <select name="billing_interval"
                        class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B]"
                        required>
                    <option value="monthly" @selected(old('billing_interval', $subscriptionPlan->billing_interval) === 'monthly')>
                        Monthly
                    </option>
                    <option value="yearly" @selected(old('billing_interval', $subscriptionPlan->billing_interval) === 'yearly')>
                        Yearly
                    </option>
                    <option value="lifetime" @selected(old('billing_interval', $subscriptionPlan->billing_interval) === 'lifetime')>
                        Lifetime
                    </option>
                    <option value="one_time" @selected(old('billing_interval', $subscriptionPlan->billing_interval) === 'one_time')>
                        One Time
                    </option>
                </select>

                @error('billing_interval')
                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
            <label class="mb-2 block text-sm font-black text-black">Stripe Price ID</label>
            <input type="text"
                name="stripe_price_id"
                value="{{ old('stripe_price_id', $subscriptionPlan->stripe_price_id) }}"
                placeholder="price_1Rxxxxxxx"
                class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B]">

            <p class="mt-2 text-xs text-black/40">
                Create the product/price in Stripe and paste the Price ID here.
            </p>

            @error('stripe_price_id')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

            <div>
                <label class="mb-2 block text-sm font-black text-black">Trial Days</label>
                <input type="number"
                       min="0"
                       name="trial_days"
                       value="{{ old('trial_days', $subscriptionPlan->trial_days ?? 0) }}"
                       class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm outline-none focus:border-[#CB148B]">

                @error('trial_days')
                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    <section class="rounded-[30px] border border-black/10 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-black text-black">Plan Includes</h2>

        <p class="mt-2 text-sm leading-6 text-black/50">
            Write one benefit per line. These benefits will be shown to users when they choose a plan.
        </p>

        <div class="mt-5">
            <textarea name="features_text"
                      rows="10"
                      placeholder="Example:
Premium habitant themes
Extra coins every month
More decoration items
Priority letter processing
Special premium badge"
                      class="w-full rounded-2xl border border-black/10 px-4 py-3 text-sm leading-7 outline-none focus:border-[#CB148B]">{{ $featuresText }}</textarea>

            @error('features_text')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </section>

    <section class="rounded-[30px] border border-black/10 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-black text-black">Visibility</h2>

        <div class="mt-5 grid gap-4 md:grid-cols-2">
            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-black/10 p-4">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox"
                       name="is_active"
                       value="1"
                       @checked(old('is_active', $subscriptionPlan->is_active ?? true))
                       class="h-5 w-5 rounded border-black/20 text-[#CB148B]">

                <div>
                    <p class="text-sm font-black text-black">Active Plan</p>
                    <p class="mt-1 text-xs text-black/45">Only active plans will be shown to users later.</p>
                </div>
            </label>

            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-black/10 p-4">
                <input type="hidden" name="is_featured" value="0">
                <input type="checkbox"
                       name="is_featured"
                       value="1"
                       @checked(old('is_featured', $subscriptionPlan->is_featured ?? false))
                       class="h-5 w-5 rounded border-black/20 text-[#CB148B]">

                <div>
                    <p class="text-sm font-black text-black">Featured Plan</p>
                    <p class="mt-1 text-xs text-black/45">Useful for marking a plan as popular or recommended.</p>
                </div>
            </label>
        </div>
    </section>

    <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">
        <a href="{{ route('admin.subscription-plans.index') }}"
           class="inline-flex items-center justify-center rounded-full border border-black/10 bg-white px-6 py-3 text-sm font-black text-black/60">
            Cancel
        </a>

        <button type="submit"
                class="inline-flex items-center justify-center rounded-full bg-[linear-gradient(135deg,#CB148B,#620A88)] px-6 py-3 text-sm font-black text-white shadow-lg shadow-fuchsia-900/10">
            {{ $buttonText }}
        </button>
    </div>
</form>