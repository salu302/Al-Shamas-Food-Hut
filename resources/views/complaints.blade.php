<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
        <div class="rounded-[32px] border border-slate-800 bg-[#181818] p-8 shadow-xl shadow-black/20">
            <div class="mb-8">
                <p class="text-sm uppercase tracking-[0.28em] text-[#FFB703]">Customer Feedback</p>
                <h1 class="mt-3 text-3xl font-semibold text-white">Complaints & Suggestions</h1>
                <p class="mt-3 text-slate-400">We value your feedback and want to improve every order experience.</p>
            </div>

            <form action="{{ route('complaints.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-slate-300">Name</label>
                        <input id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                    </div>
                    <div>
                        <label for="phone" class="mb-2 block text-sm font-semibold text-slate-300">Phone</label>
                        <input id="phone" name="phone" value="{{ old('phone') }}" required class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                    </div>
                </div>

                <div>
                    <label for="subject" class="mb-2 block text-sm font-semibold text-slate-300">Subject</label>
                    <input id="subject" name="subject" value="{{ old('subject') }}" required class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white" />
                </div>

                <div>
                    <label for="message" class="mb-2 block text-sm font-semibold text-slate-300">Message</label>
                    <textarea id="message" name="message" rows="6" required class="w-full rounded-3xl border border-slate-800 bg-[#0D0D0D] px-4 py-3 text-white">{{ old('message') }}</textarea>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="rounded-3xl bg-[#FFB703] px-6 py-3 text-sm font-semibold text-[#0D0D0D]">Submit Feedback</button>
                    <a href="{{ route('home') }}" class="rounded-3xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white">Back to Home</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
