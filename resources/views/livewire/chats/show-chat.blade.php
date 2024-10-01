<div class="flex flex-col w-full h-screen bg-gray-100">
    @if ($chat)
        <x-chat-header :chat="$chat" />

        <div id="chat-container" class="relative flex-1 overflow-auto">
            <div class="p-6">
                <div x-intersect="$wire.loadMoreMessages()"></div>

                <div class="h-full" x-cloak>
                    @forelse($messages as $index => $message)
                        @php
                            $isCurrentUser = $message->user_id === Auth::id();
                            $isLastInBlock =
                                $index === count($messages) - 1 || $messages[$index + 1]->user_id !== $message->user_id;
                            $isFirstInBlock = $index === 0 || $messages[$index - 1]->user_id !== $message->user_id;
                        @endphp

                        <x-message-bubble :chat="$chat" :message="$message" :isLastInBlock="$isLastInBlock" :isFirstInBlock="$isFirstInBlock"
                            :isCurrentUser="$isCurrentUser" />
                    @empty
                        <x-lobby />
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Message input -->
        <div class="flex items-center px-4 py-2 bg-white border-t border-gray-300">
            <button class="p-2 hover:bg-gray-100 hover:rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                </svg>
            </button>

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
    @else
        <div class="relative h-full">
            <x-lobby />
        </div>
    @endif
</div>

<script>
    function scrollToBottom(container) {
        requestAnimationFrame(() => {
            container.scrollTop = container.scrollHeight;
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        let container = document.querySelector('#chat-container');
        scrollToBottom(container);

        window.addEventListener('scrollDown', () => {
            scrollToBottom(container);
        });
    });
</script>
