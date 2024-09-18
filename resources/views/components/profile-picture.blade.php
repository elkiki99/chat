<div class="flex items-center justify-center size-12">
    @if ($user->profile_picture)
        <img class="object-cover w-full h-full rounded-full" src="{{ $user->profile_picture }}" alt="{{ $user->profile_picture }}">
    @else
        <div class="inline-block p-2 bg-gray-200 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="text-gray-300 size-8">
                <path fill-rule="evenodd"
                    d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                    clip-rule="evenodd" />
            </svg>
        </div>
    @endif
</div>