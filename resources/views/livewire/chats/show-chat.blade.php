<div class="flex flex-col w-full h-screen bg-gray-100">
    <div 
        id="chat-container" 
        class="relative flex-1 p-5 overflow-auto lg:p-10"
        {{-- style="visibility: hidden;" --}}
    >
        <div class="h-full" x-cloak>
            @if ($chat)
                @forelse($messages as $index => $message)
                    @php
                        $isLastInBlock =
                            $index === count($messages) - 1 || $messages[$index + 1]->user_id !== $message->user_id;
                        $isFirstInBlock = $index === 0 || $messages[$index - 1]->user_id !== $message->user_id;
                    @endphp

                    <x-message-bubble :message="$message" :isLastInBlock="$isLastInBlock" :isFirstInBlock="$isFirstInBlock" />
                @empty
                    <p>No messages found</p>
                @endforelse
            @else
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <x-application-logo class="block w-auto text-gray-300 fill-current size-32 dark:text-gray-200" />
                    <p class="mt-2 text-xl text-gray-700">Welcome to Chat App</p>
                    <p class="mt-2 text-sm text-gray-400">Connect with people all around the globe, or just chat a
                        friend!</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Message input -->
    <div class="flex items-center px-4 py-2 mt-4 bg-white border-t border-gray-300">
        <form wire:submit.prevent="sendMessage" class="flex w-full">
            <input type="text" wire:model="body" placeholder="Type a message here..."
                class="w-full mx-2 border-none focus:outline-none focus:ring-0" />
            <button type="submit" class="text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path
                        d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z" />
                </svg>
            </button>
        </form>
    </div>
</div>

<script>
    function scrollToBottom(container) {
        requestAnimationFrame(() => {
            container.scrollTop = container.scrollHeight;
            // container.style.visibility = 'visible';
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        let container = document.querySelector('#chat-container');
        scrollToBottom(container);
    });

    window.addEventListener('scrollDown', () => {
        let container = document.querySelector('#chat-container');
        scrollToBottom(container);
    });
</script>