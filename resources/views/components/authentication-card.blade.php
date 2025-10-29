<div class="min-h-screen flex flex-col justify-center items-center py-10">
    <div class="mb-4">
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-2 px-6 py-6 bg-white/5 border border-white/10 backdrop-blur-md shadow-xl overflow-hidden rounded-2xl">
        {{ $slot }}
    </div>
</div>
