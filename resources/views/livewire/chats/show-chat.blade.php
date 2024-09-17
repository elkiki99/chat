<div class="flex flex-col w-3/4 bg-gray-200">
    <div class="flex-1 p-10 overflow-auto">
        <div>
            @if ($chat)
                @forelse($messages as $message)
                    <!-- Chat -->
                    <div class="mb-4 flex {{ $message->user_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                        <p class="p-2 rounded shadow {{ $message->user_id === Auth::id() ? 'bg-green-200 text-gray-800' : 'bg-white text-gray-800' }} max-w-xs">
                            {{ $message->body }}
                        </p>
                    </div>
                @empty
                    <p>No messages found</p>
                @endforelse
            @else
            <div class="flex flex-col items-center justify-center">
                <x-application-logo class="block w-auto text-gray-300 fill-current size-48 dark:text-gray-200" />
                <p class="mt-4 text-gray-400">Select a chat to view messages.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Message input -->
    <div class="flex items-center px-4 py-2 mt-4 bg-white border-t border-gray-300">
        <button class="">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"
                class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
            </svg>
        </button>

        <input type="text" placeholder="Type a message here..."
            class="w-full mx-2 border-none focus:outline-none focus:ring-0" />
        <button class="text-green-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path
                    d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z" />
            </svg>
        </button>
    </div>
</div>
